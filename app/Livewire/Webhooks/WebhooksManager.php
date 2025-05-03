<?php declare(strict_types=1);

namespace App\Livewire\Webhooks;

use App\Services\WebhookMicroservice;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Client\ConnectionException;
use Livewire\Component;

final class WebhooksManager extends Component
{
    public bool $serviceIsHealthy = false;
    public array $webhooks = [];
    public ?string $webhookIdBeingDeleted = null;

    /**
     * @throws ConnectionException
     */
    public function mount(WebhookMicroservice $webhookService): void
    {
        $this->serviceIsHealthy = $webhookService->isHealthy();

        if (! $this->serviceIsHealthy) {
            session()->flash('error', 'The microservice is not available.');
            return;
        }
        $response = $webhookService->getAll();

        if ($response->successful()) {
            $json = $response->json();
            $this->webhooks = $json['webhooks'] ?? [];
        } else {
            session()->flash('error', 'Failed to load webhooks.');
        }
    }

    /**
     * @throws ConnectionException
     */
    public function checkServiceAgain(WebhookMicroservice $webhookService): void
    {
        $this->serviceIsHealthy = $webhookService->isHealthy();

        if ($this->serviceIsHealthy) {
            $response = $webhookService->getAll();
            if ($response->successful()) {
                $json = $response->json();
                $this->webhooks = $json['webhooks'] ?? [];
            } else {
                session()->flash('error', 'Failed to load webhooks.');
            }
        } else {
            $this->webhooks = [];
            session()->flash('error', 'The microservice is still not available.');
        }
    }
    public function confirmDeletion(string $id): void
    {
        $this->webhookIdBeingDeleted = $id;
    }

    /**
     * @throws ConnectionException
     */
    public function deleteWebhook(WebhookMicroservice $webhookService): void
    {
        if (! $this->webhookIdBeingDeleted) {
            return;
        }

        $response = $webhookService->delete($this->webhookIdBeingDeleted);

        if ($response->successful()) {
            session()->flash('success', 'Webhook deleted successfully.');

            $this->checkServiceAgain($webhookService);
        } else {
            $errorBody = $response->json()['message'] ?? 'Error deleting webhook.';
            session()->flash('error', $errorBody);
        }
        $this->webhookIdBeingDeleted = null;
    }

    public function render(): View|Factory|Application|\Illuminate\View\View
    {
        return view('livewire.Webhooks.webhooks-manager');
    }
}
