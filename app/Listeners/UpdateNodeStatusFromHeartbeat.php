<?php declare(strict_types=1);

namespace App\Listeners;

use App\Events\NodeHeartbeatReceived;
use App\Models\Node;
use Log;

final class UpdateNodeStatusFromHeartbeat
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(NodeHeartbeatReceived $event): void
    {
        $node = Node::where('node_uuid', $event->nodeUuid)->first();

        if (! $node) {
            Log::warning('Heartbeat received for unknown node UUID: '.$event->nodeUuid);

            return;
        }

        $node->last_seen_at = now();
        $node->online = true;
        $node->save();

        Log::info("Heartbeat processed for Node UUID: {$event->nodeUuid}");
    }
}
