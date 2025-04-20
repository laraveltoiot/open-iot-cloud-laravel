<?php declare(strict_types=1);

namespace App\Livewire\Nodes;

use App\Models\Node;
use Livewire\Component;

final class ShowNode extends Component
{
    public $nodeId;

    public $name;

    public $type;

    public $fw_version;

    public $node_uuid;

    public function mount($nodeId): void
    {
        $this->nodeId = $nodeId;
        $node = Node::findOrFail($nodeId);

        $this->name = $node->name;
        $this->type = $node->type;
        $this->fw_version = $node->fw_version;
        $this->node_uuid = $node->node_uuid;
    }

    public function render()
    {
        return view('livewire.nodes.show-node');
    }
}
