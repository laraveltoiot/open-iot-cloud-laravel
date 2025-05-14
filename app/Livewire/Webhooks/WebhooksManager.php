<?php declare(strict_types=1);

namespace App\Livewire\Webhooks;

use App\Services\WebhookMicroservice;
use Flux\Flux;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Client\ConnectionException;
use Livewire\Component;

final class WebhooksManager extends Component
{
    public bool $serviceIsHealthy = false;

    public array $webhooks = [];

    // For Delete confirmation
    public ?string $webhookIdBeingDeleted = null;

    // For Create form
    public array $createForm = [
        'name' => '',
        'url' => '',
        'method' => 'POST',
        'topic_filter' => '',
        'enabled' => true,
        'headers' => ['X-API-Key' => ''],
        'timeout' => 10,
        'retry_count' => 3,
        'retry_delay' => 5,
    ];

    // For Edit form
    public ?string $editingWebhookId = null;

    public array $editForm = [
        'name' => '',
        'url' => '',
        'method' => 'POST',
        'topic_filter' => '',
        'enabled' => false,
        'headers' => ['X-API-Key' => ''],
        'timeout' => 10,
        'retry_count' => 3,
        'retry_delay' => 5,
    ];

    /**
     * Initial mount: check health, load webhooks.
     *
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
     * Refresh from microservice (manually or after create/update/delete).
     *
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

    /**
     * -----------------------
     * CREATE WEBHOOK
     * -----------------------
     */
    public function resetCreateForm(): void
    {
        $this->createForm = [
            'name' => '',
            'url' => '',
            'method' => 'POST',
            'topic_filter' => '',
            'enabled' => true,
            'headers' => ['X-API-Key' => ''],
            'timeout' => 10,
            'retry_count' => 3,
            'retry_delay' => 5,
        ];
    }

    /**
     * @throws ConnectionException
     */
    public function storeWebhook(WebhookMicroservice $webhookService): void
    {
        if (empty($this->createForm['name']) || empty($this->createForm['url'])) {
            session()->flash('error', 'Name and URL are required.');

            return;
        }

        $response = $webhookService->create($this->createForm);

        if ($response->successful()) {
            session()->flash('success', 'Webhook created successfully.');
            $this->checkServiceAgain($webhookService);

            // Reset the form
            $this->resetCreateForm();

            // Close the create modal
            Flux::modals()->close('create-webhook');
        } else {
            $errorBody = $response->json()['message'] ?? 'Error creating webhook.';
            session()->flash('error', $errorBody);
        }
    }

    /**
     * -----------------------
     * EDIT WEBHOOK
     * -----------------------
     */
    public function editWebhook(string $id, WebhookMicroservice $webhookService): void
    {
        $this->editingWebhookId = $id;

        $response = $webhookService->find($id);
        if ($response->failed()) {
            session()->flash('error', 'Error fetching webhook data.');

            return;
        }

        $wh = $response->json()['webhook'] ?? null;
        if (! $wh) {
            session()->flash('error', 'Webhook not found.');

            return;
        }

        // Populate the edit form
        $this->editForm = [
            'name' => $wh['name'] ?? '',
            'url' => $wh['url'] ?? '',
            'method' => $wh['method'] ?? 'POST',
            'topic_filter' => $wh['topic_filter'] ?? '',
            'enabled' => $wh['enabled'] ?? false,
            'headers' => $wh['headers'] ?? [],
            'timeout' => $wh['timeout'] ?? 10,
            'retry_count' => $wh['retry_count'] ?? 3,
            'retry_delay' => $wh['retry_delay'] ?? 5,
        ];
    }

    /**
     * @throws ConnectionException
     */
    public function updateWebhook(WebhookMicroservice $webhookService): void
    {
        if (! $this->editingWebhookId) {
            session()->flash('error', 'No webhook selected to edit.');

            return;
        }

        if (empty($this->editForm['name']) || empty($this->editForm['url'])) {
            session()->flash('error', 'Name and URL are required.');

            return;
        }

        $response = $webhookService->update($this->editingWebhookId, $this->editForm);

        if ($response->successful()) {
            session()->flash('success', 'Webhook updated successfully.');
            $this->checkServiceAgain($webhookService);

            // Clear the editing ID
            $this->editingWebhookId = null;

            // Close the edit modal
            Flux::modals()->close('edit-webhook');
        } else {
            $errorBody = $response->json()['message'] ?? 'Error updating webhook.';
            session()->flash('error', $errorBody);
        }
    }

    /**
     * -----------------------
     * DELETE WEBHOOK
     * -----------------------
     */
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
