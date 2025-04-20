<?php declare(strict_types=1);

namespace App\Livewire\Nodes;

use App\Models\Node;
use App\Models\User;
use Livewire\Component;
use Str;

final class EditNode extends Component
{
    public $nodeId;

    public $name;

    public $type;

    public $fw_version;

    public array $targetUserIds = [];

    public function mount($nodeId): void
    {
        $node = Node::with('users')->findOrFail($nodeId);

        $this->name = $node->name;
        $this->type = $node->type;
        $this->fw_version = $node->fw_version;
        $this->targetUserIds = $node->users->pluck('id')->toArray();
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'fw_version' => 'required|string',
        ]);

        $node = Node::findOrFail($this->nodeId);
        $node->update([
            'name' => $this->name,
            'type' => $this->type,
            'fw_version' => $this->fw_version,
        ]);
        // Sync mapping
        $node->users()->sync(collect($this->targetUserIds)->mapWithKeys(fn ($id) => [
            $id => [
                'status' => 'confirmed',
                'secret_key' => Str::uuid(),
            ]])->toArray());
        $node->save();
        session()->flash('success', 'Node updated successfully!');

        //        Flux::modals()->close();
    }

    public function render()
    {
        return view('livewire.nodes.edit-node', [
            'users' => User::all(),
        ]);
    }
}
