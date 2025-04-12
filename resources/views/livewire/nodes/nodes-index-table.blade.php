<div>
    <flux:heading size="xl">Nodes</flux:heading>
    <flux:text class="mt-2">Nodes represent individual IoT devices registered in the system. Each node includes metadata such as type,
        firmware version, and registration date.</flux:text>

   <div class="mt-3">
       <flux:input placeholder="Search nodes..." wire:model.live="search" class="w-64" />
   </div>

    <flux:table :paginate="$this->nodes" class="mt-4">
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Name</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'type'" :direction="$sortDirection" wire:click="sort('type')">Type</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'fw_version'" :direction="$sortDirection" wire:click="sort('fw_version')">FW Version</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">Created</flux:table.column>
            <flux:table.column></flux:table.column> {{-- Actions --}}
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->nodes as $node)
                <flux:table.row :key="$node->id">
                    <flux:table.cell> {!! $this->highlight($node->name) !!}</flux:table.cell>
                    <flux:table.cell>{!! $this->highlight($node->type) !!} </flux:table.cell>
                    <flux:table.cell>{{ $node->fw_version }}</flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">{{ $node->created_at->format('Y-m-d') }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

</div>
