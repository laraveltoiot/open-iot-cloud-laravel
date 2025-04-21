<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Node;
use App\Models\UserNodeMapping;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\ConnectionNotAvailableException;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Exceptions\ProtocolNotSupportedException;
use PhpMqtt\Client\Facades\MQTT;

final class MqttPairingListenerCommand extends Command
{
    protected $signature = 'mqtt:subscribe';

    protected $description = 'Subscribe to MQTT pairing topics and process incoming messages';

    /**
     * @throws ConnectionNotAvailableException
     * @throws ConnectingToBrokerFailedException
     * @throws MqttClientException
     * @throws ConfigurationInvalidException
     * @throws BindingResolutionException
     * @throws ProtocolNotSupportedException
     */
    public function handle(): void
    {
        $client = MQTT::connection('hivemq');
        try {
            $client->subscribe('node/+/user/mapping', function (string $topic, string $message) {
                $this->info("Received message on {$topic}: {$message}");

                // Parse topic and message
                $parts = explode('/', $topic);
                $nodeUuid = $parts[1] ?? null;

                if (! $nodeUuid) {
                    Log::warning('MQTT: Invalid topic, node UUID missing');

                    return;
                }

                $data = json_decode($message, true);

                if (! isset($data['secret_key'])) {
                    Log::warning("MQTT: Secret key missing in message for node {$nodeUuid}");

                    return;
                }

                // Find node and mapping
                $node = Node::where('node_uuid', $nodeUuid)->first();
                if (! $node) {
                    Log::warning("MQTT: Node not found with UUID {$nodeUuid}");

                    return;
                }

                $mapping = UserNodeMapping::where('node_id', $node->id)
                    ->where('secret_key', $data['secret_key'])
                    ->where('status', 'requested')
                    ->first();

                if (! $mapping) {
                    Log::warning("MQTT: No matching mapping for node {$nodeUuid} and secret key {$data['secret_key']}");

                    return;
                }

                $mapping->status = 'confirmed';
                $mapping->save();

                Log::info("MQTT: Node {$nodeUuid} paired successfully with user ID {$mapping->user_id}");
            }, 1);

            $this->info('Subscribed to topic: node/+/user/mapping');
            $client->loop(true); // Blocking loop
        } catch (MqttClientException $e) {
            Log::error('MQTT: Subscription error - '.$e->getMessage());
            $this->error('MQTT Error: '.$e->getMessage());
        }
    }
}
