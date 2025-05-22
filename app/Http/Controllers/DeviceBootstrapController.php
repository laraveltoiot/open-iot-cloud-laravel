<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Node;
use App\Models\UserNodeMapping;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class DeviceBootstrapController extends Controller
{
    /**
     * Bootstrap a device by handling both pairing and credential retrieval in a single request.
     * This implements a Zero-Touch Provisioning approach for IoT devices.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'node_uuid' => ['required', 'uuid'],
            'secret_key' => ['required', 'uuid'],
        ]);

        $nodeUuid = $validated['node_uuid'];
        $secretKey = $validated['secret_key'];

        $node = Node::where('node_uuid', $nodeUuid)->first();
        if (! $node) {
            Log::warning('Bootstrap: Node not found', ['node_uuid' => $nodeUuid]);

            return response()->json([
                'status' => 'error',
                'message' => 'Node not found',
            ], 404);
        }

        $mapping = UserNodeMapping::where('node_id', $node->id)
            ->where('secret_key', $secretKey)
            ->first();

        if (! $mapping) {
            Log::warning('Bootstrap: Invalid credentials', [
                'node_uuid' => $nodeUuid,
                'provided_key' => $secretKey,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 403);
        }

        // If mapping exists but not confirmed, confirm it
        if ($mapping->status === 'requested') {
            $mapping->status = 'confirmed';
            $mapping->save();

            Log::info('Bootstrap: Mapping confirmed', [
                'node_uuid' => $nodeUuid,
                'user_id' => $mapping->user_id,
            ]);

            // Generate MQTT credentials if they don't exist
            if (! $node->mqtt_username) {
                $node->mqtt_username = 'node-'.$node->node_uuid;
                $node->mqtt_password = Str::uuid()->toString();
                $node->mqtt_broker = config('mqtt.default_broker_host', 'mqtt.my-cloud.com');
                $node->mqtt_port = config('mqtt.default_broker_port', 8883);
                $node->save();

                Log::info('Bootstrap: MQTT credentials generated', ['node_uuid' => $nodeUuid]);
            }
        }

        // Return all necessary configuration in one response
        return response()->json([
            'status' => 'success',
            'broker' => $node->mqtt_broker,
            'port' => $node->mqtt_port,
            'username' => $node->mqtt_username,
            'password' => $node->mqtt_password,
            'topics' => [
                'config_publish' => "node/{$node->node_uuid}/config",
                'params_init' => "node/{$node->node_uuid}/params/local/init",
                'params_publish' => "node/{$node->node_uuid}/params/local",
                'params_subscribe' => "node/{$node->node_uuid}/params/remote",
            ],
        ]);
    }
}
