<div>
    @if (session()->has('success'))
        <div class="text-green-500">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <flux:input label="Name" type="text" wire:model.defer="name" />
        </div>

        <div>
            <flux:input label="Type" wire:model.defer="type" />
        </div>

        <div>
            <flux:input label="Firmware Version" wire:model.defer="fw_version" />
        </div>

        <div>
            <flux:select
                wire:model.defer="targetUserIds"
                variant="listbox"
                searchable
                multiple
                placeholder="Select user to assign"
            >
                @foreach ($users as $user)
                    <flux:select.option value="{{ $user->id }}">
                        {{ $user->name }} ({{ $user->email }})
                    </flux:select.option>
                @endforeach
            </flux:select>
        </div>


        <flux:button type="submit">
            Create Node
        </flux:button>
    </form>
</div>
