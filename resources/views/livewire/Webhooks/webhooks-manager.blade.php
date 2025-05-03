<div>
    @if (session()->has('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
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

                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    wire:click="deleteWebhook('{{ $webhook['id'] }}')"
                                >
                                    Delete
                                </flux:button>
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
    @endif
</div>
