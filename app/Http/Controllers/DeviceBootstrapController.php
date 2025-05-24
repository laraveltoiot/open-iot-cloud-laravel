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
        // Validăm datele primite
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

        if ($mapping->status === 'requested') {
            $mapping->status = 'confirmed';
            $mapping->save();

            Log::info('Bootstrap: Mapping confirmed', [
                'node_uuid' => $nodeUuid,
                'user_id' => $mapping->user_id,
            ]);
        }
        $mqttUsername = 'node-' . $node->node_uuid;
        $mqttPassword = (string) Str::uuid();

        $broker = config('mqtt-client.connections.hivemq.host');
        $port   = config('mqtt-client.connections.hivemq.port');

        $authUsername = config('mqtt-client.connections.hivemq.connection_settings.auth.username');
        $authPassword = config('mqtt-client.connections.hivemq.connection_settings.auth.password');

        return response()->json([
            'status' => 'success',
            'broker' => $broker,
            'port' => $port,
            'username' => $mqttUsername,
            'password' => $mqttPassword,
            'auth_username' => $authUsername,
            'auth_password' => $authPassword,

            'topics' => [
                'config_publish' => "node/{$node->node_uuid}/config",
                'params_init' => "node/{$node->node_uuid}/params/local/init",
                'params_publish' => "node/{$node->node_uuid}/params/local",
                'params_subscribe' => "node/{$node->node_uuid}/params/remote",
            ],
        ]);
    }
}
