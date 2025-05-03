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
        {{-- CREATE BUTTON / TRIGGER --}}
        <flux:modal.trigger name="create-webhook" wire:click="resetCreateForm">
            <flux:button variant="primary" class="mb-4">Create Webhook</flux:button>
        </flux:modal.trigger>

        @if (count($webhooks) === 0)
            <p>No webhooks found.</p>
        @else
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>ID</flux:table.column>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>URL</flux:table.column>
                    <flux:table.column>Method</flux:table.column>
                    <flux:table.column>Topic Filter</flux:table.column>
                    <flux:table.column>Enabled</flux:table.column>
                    <flux:table.column>Headers</flux:table.column>
                    <flux:table.column>Timeout</flux:table.column>
                    <flux:table.column>Retry Count</flux:table.column>
                    <flux:table.column>Retry Delay</flux:table.column>
                    <flux:table.column>Created</flux:table.column>
                    <flux:table.column>Updated</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($webhooks as $webhook)
                        <flux:table.row :key="$webhook['id']">
                            <flux:table.cell>
                                {{ $webhook['id'] ?? '' }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $webhook['name'] ?? '' }}
                            </flux:table.cell>

                            <flux:table.cell class="truncate">
                                {{ $webhook['url'] ?? '' }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $webhook['method'] ?? 'N/A' }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $webhook['topic_filter'] ?? '' }}
                            </flux:table.cell>

                            <flux:table.cell>
                                @if(!empty($webhook['enabled']))
                                    <span class="text-green-600 font-bold">Yes</span>
                                @else
                                    <span class="text-gray-500">No</span>
                                @endif
                            </flux:table.cell>

                            <flux:table.cell class="whitespace-pre-wrap">
                                {{-- Example: display headers as JSON --}}
                                @if(!empty($webhook['headers']))
                                    <code>{{ json_encode($webhook['headers']) }}</code>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $webhook['timeout'] ?? '' }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $webhook['retry_count'] ?? '' }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $webhook['retry_delay'] ?? '' }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $webhook['created_at'] ?? '' }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $webhook['updated_at'] ?? '' }}
                            </flux:table.cell>

                            <flux:table.cell class="flex gap-2">
                                {{-- EDIT BUTTON / TRIGGER --}}
                                <flux:modal.trigger
                                    name="edit-webhook"
                                    wire:click="editWebhook('{{ $webhook['id'] }}')"
                                    :key="'edit-'.$webhook['id']"
                                >
                                    <flux:button variant="subtle" size="sm">
                                        Edit
                                    </flux:button>
                                </flux:modal.trigger>

                                {{-- DELETE BUTTON / TRIGGER --}}
                                <flux:modal.trigger
                                    name="delete-webhook"
                                    wire:click="confirmDeletion('{{ $webhook['id'] }}')"
                                    :key="'delete-'.$webhook['id'].'.'.($webhookIdBeingDeleted ?? '')"
                                >
                                    <flux:button variant="danger" size="sm">
                                        Delete
                                    </flux:button>
                                </flux:modal.trigger>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @endif

        <flux:button variant="primary" class="mt-4" wire:click="checkServiceAgain">
            Refresh
        </flux:button>

        {{-- CREATE WEBHOOK MODAL --}}
        <flux:modal name="create-webhook" class="w-[35rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Create Webhook</flux:heading>
                    <p class="text-sm text-gray-600 mt-2">
                        Fill the form to create a new webhook.
                    </p>
                </div>

                <flux:input
                    wire:model="createForm.name"
                    label="Name"
                    placeholder="Webhook name..."
                />

                <flux:input
                    wire:model="createForm.url"
                    label="URL"
                    placeholder="https://example.com/api/webhook"
                />

                <flux:input
                    wire:model="createForm.topic_filter"
                    label="Topic Filter"
                    placeholder="sensors/temperature"
                />

                <div class="flex gap-4">
                    <flux:select
                        wire:model="createForm.method"
                        label="Method"
                    >
                        <option>POST</option>
                        <option>GET</option>
                        <option>PUT</option>
                        <option>DELETE</option>
                    </flux:select>

                    <flux:switch
                        wire:model="createForm.enabled"
                        label="Enabled"
                        :value="true"
                    />
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <flux:input wire:model="createForm.timeout" label="Timeout" />
                    <flux:input wire:model="createForm.retry_count" label="Retry Count" />
                    <flux:input wire:model="createForm.retry_delay" label="Retry Delay" />
                </div>

                <flux:input
                    wire:model="createForm.headers.X-API-Key"
                    label="X-API-Key"
                    placeholder="secret-key"
                />

                <div class="flex">
                    <flux:spacer />
                    {{-- Save calls storeWebhook --}}
                    <flux:button
                        type="submit"
                        variant="primary"
                        wire:click="storeWebhook"
                    >
                        Save
                    </flux:button>
                </div>
            </div>
        </flux:modal>

        {{-- EDIT WEBHOOK MODAL --}}
        <flux:modal name="edit-webhook" class="w-[35rem]" wire:key="'edit-modal-'.$editingWebhookId">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Edit Webhook</flux:heading>
                    <p class="text-sm text-gray-600 mt-2">
                        Modify an existing webhook.
                    </p>
                </div>

                <flux:input
                    wire:model="editForm.name"
                    label="Name"
                    placeholder="Webhook name..."
                />

                <flux:input
                    wire:model="editForm.url"
                    label="URL"
                    placeholder="https://example.com/api/webhook"
                />

                <flux:input
                    wire:model="editForm.topic_filter"
                    label="Topic Filter"
                />

                <div class="flex gap-4">
                    <flux:select
                        wire:model="editForm.method"
                        label="Method"
                    >
                        <option>POST</option>
                        <option>GET</option>
                        <option>PUT</option>
                        <option>DELETE</option>
                    </flux:select>

                    <flux:switch
                        wire:model="editForm.enabled"
                        label="Enabled"
                        :value="true"
                    />
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <flux:input wire:model="editForm.timeout" label="Timeout" />
                    <flux:input wire:model="editForm.retry_count" label="Retry Count" />
                    <flux:input wire:model="editForm.retry_delay" label="Retry Delay" />
                </div>

                <flux:input
                    wire:model="editForm.headers.X-API-Key"
                    label="X-API-Key"
                />

                <div class="flex">
                    <flux:spacer />
                    {{-- Update calls updateWebhook --}}
                    <flux:button
                        type="submit"
                        variant="primary"
                        wire:click="updateWebhook"
                    >
                        Update
                    </flux:button>
                </div>
            </div>
        </flux:modal>

        {{-- DELETE WEBHOOK MODAL --}}
        <flux:modal name="delete-webhook" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete Webhook?</flux:heading>
                    <p class="text-sm text-gray-600 mt-2">
                        Are you sure you want to delete this webhook?
                        This action cannot be undone.
                    </p>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>

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
