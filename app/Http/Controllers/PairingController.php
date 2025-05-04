<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Node;
use App\Models\UserNodeMapping;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class PairingController extends Controller
{
    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'topic' => ['required', 'string'],
            'payload.secret_key' => ['required', 'uuid'],
        ]);

        $topicParts = explode('/', $validated['topic']);

        if (
            count($topicParts) < 4 ||
            $topicParts[0] !== 'node' ||
            $topicParts[2] !== 'user' ||
            $topicParts[3] !== 'mapping'
        ) {
            Log::warning('Pairing: Invalid topic format', ['topic' => $validated['topic']]);

            return response(['status' => 'error', 'message' => 'Invalid topic format'], 400);
        }

        $nodeUuid = $topicParts[1];
        $secretKey = $validated['payload']['secret_key'];

        $node = Node::where('node_uuid', $nodeUuid)->first();
        if (! $node) {
            Log::warning('Pairing: Node not found', ['node_uuid' => $nodeUuid]);

            return response(['status' => 'error', 'message' => 'Node not found'], 404);
        }

        $mapping = UserNodeMapping::where('node_id', $node->id)
            ->where('secret_key', $secretKey)
            ->where('status', 'requested')
            ->first();

        if (! $mapping) {
            Log::warning('Pairing: No matching mapping found', ['node_uuid' => $nodeUuid, 'secret_key' => $secretKey]);

            return response(['status' => 'error', 'message' => 'Mapping not found or already confirmed'], 404);
        }

        // Mark as confirmed
        $mapping->status = 'confirmed';
        $mapping->save();

        // Generate MQTT credentials only if they don't already exist
        if (! $node->mqtt_username) {
            $node->mqtt_username = 'node-'.$node->node_uuid;
            // or any random generation
            $node->mqtt_password = Str::uuid()->toString();
            $node->mqtt_broker = config('mqtt.default_broker_host', 'mqtt.my-cloud.com');
            $node->mqtt_port = config('mqtt.default_broker_port', 8883);

            $node->save();
        }

        Log::info('Pairing successful', [
            'node_uuid' => $nodeUuid,
            'user_id' => $mapping->user_id,
        ]);

        return response([
            'status' => 'success',
            'message' => 'Node paired successfully',
        ], 200);
    }
}
