<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('API')" :subheading=" __('Token management')">
        <h3>Create API Token</h3>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            API tokens allow third-party services to authenticate with our application on your behalf.
        </p>

        <form wire:submit.prevent="createToken" class="mt-6 space-y-6">
            <div>
                <flux:label badge="Required">Token Name</flux:label>
                <flux:input wire:model="createForm.name" type="text" class="mt-2" />
                <flux:error name="createForm.name" />
            </div>

            <div>
                <flux:checkbox.group label="Permissions">
                    @foreach($this->abilities as $permission => $label)
                        <flux:checkbox label="{{ $label }}" wire:model="createForm.permissions" value="{{ $permission }}"/>
                    @endforeach
                </flux:checkbox.group>
            </div>

            <div class="flex items-center justify-end">
                <flux:button type="submit">
                    Create
                </flux:button>
            </div>
        </form>

        @if($tokens->isNotEmpty())
            <div class="border-t border-gray-200 dark:border-gray-600 my-6"></div>
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Manage API Tokens</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        You may delete any of your existing tokens if they are no longer needed.
                    </p>

                    <div class="mt-6 space-y-4">
                        @foreach($tokens as $token)
                            <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-600">
                                <div class="break-all">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $token->name }}</div>
                                    @if($token->last_used_at)
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Last used {{ $token->last_used_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-4">
                                    <flux:button wire:click="managePermissions('{{ $token->id }}')">
                                        Permissions
                                    </flux:button>
                                    <flux:button wire:click="confirmDeletion('{{ $token->id }}')">
                                        Delete
                                    </flux:button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Modals -->
        @if($displayToken)
            <div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">API Token</h3>
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Please copy your new API token. For your security, it won't be shown again.
                        </p>
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <code class="block break-all font-mono text-sm dark:text-gray-200">{{ $plainTextToken }}</code>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <flux:button wire:click="$set('displayToken', false)">
                            Close
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif

        @if($managingPermissions)
            <div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">API Token Permissions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($this->abilities as $permission => $label)
                            <flux:checkbox label="{{$label}}" wire:model="updateForm.permissions" value="{{ $permission }}"/>
                            <flux:error name="updateForm.permissions" />
                        @endforeach
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <flux:button wire:click="$set('managingPermissions', false)">
                            Cancel
                        </flux:button>
                        <flux:button wire:click="updatePermissions">
                            Save
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif

        @if($confirmingDeletion)
            <div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Delete API Token</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Are you sure you would like to delete this API token?
                    </p>
                    <div class="mt-6 flex justify-end gap-3">
                        <flux:button wire:click="$set('confirmingDeletion', false)">
                            Cancel
                        </flux:button>
                        <flux:button wire:click="deleteToken">
                            Delete
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif

    </x-settings.layout>
</section>
