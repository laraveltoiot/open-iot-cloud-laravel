<div>
    @if (session()->has('error'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition
            class="bg-red-100 text-red-700 p-3 rounded mb-4"
        >
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition
            class="bg-green-100 text-green-700 p-3 rounded mb-4"
        >
            {{ session('success') }}
        </div>
    @endif


    <h2 class="text-xl font-bold mb-4">Webhook Management</h2>

    @if (!$serviceIsHealthy)
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            The microservice is not available.
        </div>
        <flux:button variant="primary" wire:click="checkServiceAgain">
            Check Again
        </flux:button>
    @else
        <!-- If service is healthy, show the table -->
        @if (count($webhooks) === 0)
            <p>No webhooks found.</p>
        @else
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>URL</flux:table.column>
                    <flux:table.column>Method</flux:table.column>
                    <flux:table.column>Enabled</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($webhooks as $webhook)
                        <flux:table.row :key="$webhook['id']">
                            <flux:table.cell>
                                {{ $webhook['name'] ?? '' }}
                            </flux:table.cell>

                            <flux:table.cell class="truncate">
                                {{ $webhook['url'] ?? '' }}
                            </flux:table.cell>

                            <flux:table.cell>
                                <!-- Example, display method as a string -->
                                {{ $webhook['method'] ?? 'N/A' }}
                            </flux:table.cell>

                            <flux:table.cell>
                                @if(isset($webhook['enabled']) && $webhook['enabled'])
                                    <span class="text-green-600 font-bold">Yes</span>
                                @else
                                    <span class="text-gray-500">No</span>
                                @endif
                            </flux:table.cell>

                            <flux:table.cell>
                                <!-- Example buttons -->
                                <flux:button
                                    variant="subtle"
                                    size="sm"
                                    wire:click="editWebhook('{{ $webhook['id'] }}')"
                                >
                                    Edit
                                </flux:button>
                                <flux:modal.trigger name="delete-webhook" wire:click="confirmDeletion('{{ $webhook['id'] }}')">
                                    <flux:button variant="danger" size="sm">Delete</flux:button>
                                </flux:modal.trigger>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @endif

        <!-- Provide a button to re-check the service or refresh -->
        <flux:button variant="primary" class="mt-4" wire:click="checkServiceAgain">
            Refresh
        </flux:button>
            <!-- The actual modal (only one) -->
            <flux:modal name="delete-webhook" class="min-w-[22rem]">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Delete Webhook?</flux:heading>
                        <p class="text-sm text-gray-600 mt-2">
                            Are you sure you want to delete this webhook? This action cannot be undone.
                        </p>
                    </div>

                    <div class="flex gap-2">
                        <flux:spacer />

                        <!-- "Cancel" just closes the modal -->
                        <flux:modal.close>
                            <flux:button variant="ghost">Cancel</flux:button>
                        </flux:modal.close>

                        <!-- Confirm: calls Livewire deleteWebhook() then closes -->
                        <flux:button
                            variant="danger"
                            wire:click="deleteWebhook"
                            flux:modal.close
                        >
                            Delete
                        </flux:button>
                    </div>
                </div>
            </flux:modal>
    @endif
</div>
