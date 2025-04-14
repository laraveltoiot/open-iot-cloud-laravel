<?php declare(strict_types=1);

namespace App\Livewire\Nodes;

use App\Models\Node;
use Illuminate\Support\Str;
use Livewire\Component;

final class CreateNodeForm extends Component
{
    protected array $rules = [
        'name' => 'required|string|max:255',
        'type' => 'nullable|string|max:255',
        'fw_version' => 'nullable|string|max:255',
    ];

    public string $name;

    public string $type;

    public string $fw_version;

    public function save(): void
    {
        $validated = $this->validate();

        Node::create([
            'node_uuid' => Str::uuid(),
            'name' => $validated['name'],
            'type' => $validated['type'] ?? null,
            'fw_version' => $validated['fw_version'] ?? null,
        ]);

        session()->flash('success', 'Node created successfully!');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.nodes.create-node-form');
    }
}
