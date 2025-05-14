<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Node;
use App\Models\UserNodeMapping;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class DeviceProvisioningController extends Controller
{
    public function show(Request $request, string $node_uuid): JsonResponse
    {
        $secretKey = $request->query('key');

        if (! $secretKey || ! is_string($secretKey)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing or invalid secret key',
            ], 400);
        }

        $node = Node::where('node_uuid', $node_uuid)->first();

        if (! $node) {
            return response()->json([
                'status' => 'error',
                'message' => 'Node not found',
            ], 404);
        }

        $mapping = UserNodeMapping::where('node_id', $node->id)
            ->where('secret_key', $secretKey)
            ->where('status', 'confirmed')
            ->first();

        if (! $mapping) {
            Log::warning('Provisioning: invalid or unauthorized secret key', [
                'node_uuid' => $node_uuid,
                'provided_key' => $secretKey,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        // All good — return credentials
        return response()->json([
            'broker' => $node->mqtt_broker,
            'port' => $node->mqtt_port,
            'username' => $node->mqtt_username,
            'password' => $node->mqtt_password,
        ]);
    }
}
