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

            <div
                x-data="{
        copied: false,
        copyUUID() {
            const uuid = '{{ $node_uuid }}';
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(uuid)
                    .then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 1500);
                    })
                    .catch(() => {
                        this.copied = false;
                    });
            } else {
                let textarea = document.createElement('textarea');
                textarea.value = uuid;
                textarea.style.position = 'fixed';
                document.body.appendChild(textarea);
                textarea.focus();
                textarea.select();
                try {
                    if (document.execCommand('copy')) {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 1500);
                    } else {
                        this.copied = false;
                    }
                } catch (err) {
                    this.copied = false;
                }
                document.body.removeChild(textarea);
            }
            return true;
        }
    }"
                class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
            >
            <dt class="text-sm/6 font-medium text-gray-900">UUID</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0 flex items-center gap-2">
                    <span class="inline-flex items-center rounded bg-gray-100 px-2 py-1 text-xs font-mono text-gray-800">
                        {{ $node_uuid }}
                    </span>
                    <button
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-200 hover:bg-gray-300 rounded transition focus:outline-none"
                        @click="copyUUID"
                        type="button"
                    >
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2" stroke="currentColor" stroke-width="2" fill="none"/>
                            <rect x="3" y="3" width="13" height="13" rx="2" ry="2" stroke="currentColor" stroke-width="2" fill="none"/>
                        </svg>
                        Copy
                    </button>
                    <span
                        x-show="copied"
                        x-transition
                        class="text-green-600 text-xs font-semibold ml-2"
                    >Copied!</span>
                </dd>
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
</div>
