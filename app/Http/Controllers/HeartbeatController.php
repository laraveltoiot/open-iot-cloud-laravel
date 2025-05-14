<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\NodeHeartbeatReceived;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class HeartbeatController extends Controller
{
    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'node_uuid' => ['required', 'uuid'],
            'timestamp' => ['nullable', 'date'],
        ]);

        event(new NodeHeartbeatReceived(
            $validated['node_uuid'],
            $validated['timestamp'] ?? now()->toISOString()
        ));

        return response([
            'status' => 'success',
            'message' => 'Heartbeat received',
        ], 200);
    }
}
