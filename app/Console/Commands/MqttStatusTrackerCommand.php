<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Node;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\ConnectionNotAvailableException;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Exceptions\ProtocolNotSupportedException;
use PhpMqtt\Client\Facades\MQTT;

final class MqttStatusTrackerCommand extends Command
{
    protected $signature = 'mqtt:status-tracker';

    protected $description = 'Subscribe to node status topics and update online/offline state in the database';

    /**
     * @throws ConnectionNotAvailableException
     * @throws ConnectingToBrokerFailedException
     * @throws ConfigurationInvalidException
     * @throws BindingResolutionException
     * @throws ProtocolNotSupportedException
     */
    public function handle(): void
    {
        $client = MQTT::connection('hivemq');

        try {
            // Subscribe to retained status messages (online/offline)
            $client->subscribe('status/+', function (string $topic, string $message) {
                $this->info("Received message on {$topic}: {$message}");

                $parts = explode('/', $topic);
                $nodeUuid = $parts[1] ?? null;

                if (! $nodeUuid) {
                    Log::warning('MQTT status topic malformed: '.$topic);

                    return;
                }

                $node = Node::where('node_uuid', $nodeUuid)->first();

                if (! $node) {
                    Log::warning("MQTT: Unknown node UUID {$nodeUuid} in status topic");

                    return;
                }

                $online = mb_trim($message) === 'online';
                $node->online = $online ? 1 : 0;
                $node->last_seen_at = now()->toDateTimeString();
                $node->save();

                Log::info("MQTT: Node {$nodeUuid} is now ".($online ? 'ONLINE' : 'OFFLINE'));
            }, 1);

            $this->info('Subscribed to topic: status/+');
            $client->loop(true); // Infinite loop – process MQTT messages
        } catch (MqttClientException $e) {
            Log::error('MQTT: Status tracker error - '.$e->getMessage());
            $this->error('MQTT Error: '.$e->getMessage());
        }
    }
}
