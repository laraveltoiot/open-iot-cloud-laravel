<?php declare(strict_types=1);

namespace App\Livewire\Nodes;

use App\Models\Node;
use Livewire\Component;

final class EditNode extends Component
{
    public $nodeId;

    public $name;

    public $type;

    public $fw_version;

    public function mount($nodeId): void
    {
        $this->nodeId = $nodeId;
        $node = Node::findOrFail($nodeId);

        $this->name = $node->name;
        $this->type = $node->type;
        $this->fw_version = $node->fw_version;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'fw_version' => 'required|string',
        ]);

        $node = Node::findOrFail($this->nodeId);
        $node->name = $this->name;
        $node->type = $this->type;
        $node->fw_version = $this->fw_version;
        $node->save();

        session()->flash('success', 'Node updated successfully!');

        //        Flux::modals()->close();
    }

    public function render()
    {
        return view('livewire.nodes.edit-node');
    }
}
