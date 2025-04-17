<x-layouts.app :title="__('Edit Node')">
    @livewire('nodes.edit-node', ['nodeId' => $node->id])
</x-layouts.app>
