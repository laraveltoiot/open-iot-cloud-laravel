<div>
    <flux:modal name="delete-node-modal" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete node?</flux:heading>

                <flux:text class="mt-2">
                    <p>You're about to delete this node.</p>
                    <p>This action cannot be reversed.</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <form wire:submit.prevent="deleteNode">
                    <flux:button type="submit" variant="danger">Delete node</flux:button>
                </form>
            </div>
        </div>
    </flux:modal>

    <flux:heading size="xl">Nodes</flux:heading>
    <div class="flex justify-end mb-4">
        <flux:link class="text-[red]" href="{{ route('nodes.create') }}">
            Add Node
        </flux:link>
    </div>

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
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->nodes as $node)
                <flux:table.row :key="$node->id">
                    <flux:table.cell> {!! $this->highlight($node->name) !!}</flux:table.cell>
                    <flux:table.cell>{!! $this->highlight($node->type) !!} </flux:table.cell>
                    <flux:table.cell>{{ $node->fw_version }}</flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">{{ $node->created_at->format('Y-m-d') }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown position="bottom" align="end">
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />

                            <flux:menu>
                                <flux:menu.item href="{{ route('nodes.show', $node->id) }}" icon="eye">View</flux:menu.item>
                                <flux:menu.item href="{{ route('nodes.edit', $node->id) }}" icon="pencil">Edit</flux:menu.item>
                                <flux:menu.item
                                    icon="trash"
                                    variant="danger"
                                    wire:click="confirmDelete({{ $node->id }})"
                                >
                                    <flux:modal.trigger name="delete-node-modal">Delete</flux:modal.trigger>
                                </flux:menu.item>

                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>


                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

</div>
