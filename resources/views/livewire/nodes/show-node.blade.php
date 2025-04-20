<div>
    <div class="px-4 sm:px-0">
        <h3 class="text-base/7 font-semibold text-gray-900">Node Information</h3>
        <p class="mt-1 max-w-2xl text-sm/6 text-gray-500">Details and technical information about the node.</p>
    </div>
    <div class="mt-6 border-t border-gray-100">
        <dl class="divide-y divide-gray-100">

            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">Node Name</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $name }}</dd>
            </div>

            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">Type</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $type ?? '-' }}</dd>
            </div>

            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">Firmware version</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $fw_version ?? '-' }}</dd>
            </div>

            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
            >
            <dt class="text-sm/6 font-medium text-gray-900">
                <flux:input
                    icon="key"
                    label="Node UUID"
                    value="{{ $node_uuid }}"
                    readonly
                    copyable
                />
            </dt>
            </div>

            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">Actions</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0 flex gap-2">
                    <a href="{{ route('nodes.edit', $nodeId) }}"
                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded shadow hover:bg-blue-700 transition">
                        Edit Node
                    </a>
                    <a href="{{ route('nodes.index') }}"
                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-900 bg-gray-100 rounded shadow hover:bg-gray-200 transition">
                        Back to List
                    </a>
                </dd>
            </div>

        </dl>
    </div>
    @if (count($userMappings))
        <div class="mt-6 border-t border-gray-200 pt-6">
            <h4 class="text-base font-semibold text-gray-800 mb-4">Assigned Users</h4>

            <ul class="space-y-4">
                @foreach ($userMappings as $user)
                    <li class="border rounded p-4 bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900">{{ $user['name'] }} <span class="text-xs text-gray-500">({{ $user['email'] }})</span></p>
                                <p class="text-sm text-gray-700">Status: <strong>{{ ucfirst($user['status']) }}</strong></p>
                                <p class="text-sm text-gray-700">Assigned at: {{ $user['assigned_at'] }}</p>
                                @if ($user['secret_key'])
                                    <flux:input
                                        icon="key"
                                        label="Secret Key"
                                        value="{{ $user['secret_key'] }}"
                                        readonly
                                        copyable
                                        class="mt-2"
                                    />
                                @else
                                    <p class="text-sm text-gray-700">Secret Key: <em class="text-gray-400">Not available</em></p>
                                @endif

                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
