<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

final class WebhookMicroservice
{
    private string $baseUri;

    private ?string $apiKey;

    public function __construct()
    {
        $this->baseUri = config('services.mqtt_microservice.base_uri');
        $this->apiKey = config('services.mqtt_microservice.api_key');
    }

    /**
     * Check if the microservice is healthy.
     *
     * @return bool True if the microservice returns {"status": "ok"} at /healthz
     */
    public function isHealthy(): bool
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(2)
                ->get("{$this->baseUri}/healthz");

            // Must be a 2xx response and body.status == 'ok'
            if ($response->ok()) {
                $json = $response->json();

                return isset($json['status']) && $json['status'] === 'ok';
            }

            return false;
        } catch (Throwable $e) {
            // If the microservice is down or unreachable, we get an exception
            return false;
        }
    }

    /**
     * @throws ConnectionException
     */
    public function getAll(): Response
    {
        return Http::withHeaders($this->headers())
            ->get("{$this->baseUri}/webhooks");
    }

    /**
     * @throws ConnectionException
     */
    public function create(array $payload): Response
    {
        return Http::withHeaders($this->headers())
            ->post("{$this->baseUri}/webhooks", $payload);
    }

    /**
     * @throws ConnectionException
     */
    public function find(string $id): Response
    {
        return Http::withHeaders($this->headers())
            ->get("{$this->baseUri}/webhooks/{$id}");
    }

    /**
     * @throws ConnectionException
     */
    public function update(string $id, array $payload): Response
    {
        return Http::withHeaders($this->headers())
            ->put("{$this->baseUri}/webhooks/{$id}", $payload);
    }

    /**
     * @throws ConnectionException
     */
    public function delete(string $id): Response
    {
        return Http::withHeaders($this->headers())
            ->delete("{$this->baseUri}/webhooks/{$id}");
    }

    private function headers(): array
    {
        $headers = [
            'Accept' => 'application/json',
        ];

        if ($this->apiKey) {
            $headers['Authorization'] = 'Bearer '.$this->apiKey;
        }

        return $headers;
    }
}
