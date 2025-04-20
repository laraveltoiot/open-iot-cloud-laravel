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
    public array $userMappings = [];

    public function mount($nodeId): void
    {
        $this->nodeId = $nodeId;

        $node = Node::with('users')->findOrFail($nodeId);

        $this->name = $node->name;
        $this->type = $node->type;
        $this->fw_version = $node->fw_version;
        $this->node_uuid = $node->node_uuid;

        $this->userMappings = $node->users->map(function ($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->pivot->status,
                'secret_key' => $user->pivot->secret_key,
                'assigned_at' => $user->pivot->created_at->format('Y-m-d H:i'),
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.nodes.show-node');
    }
}
