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

    // Stub for edit/delete methods you might later implement
    public function editWebhook($id): void
    {
        session()->flash('error', 'Edit not implemented yet.');
    }

    public function deleteWebhook($id): void
    {
        session()->flash('error', 'Delete not implemented yet.');
    }

    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        return view('livewire.Webhooks.webhooks-manager');
    }
}
