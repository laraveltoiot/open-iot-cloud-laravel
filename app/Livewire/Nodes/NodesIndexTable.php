<?php declare(strict_types=1);

namespace App\Livewire\Nodes;

use App\Livewire\Traits\HighlightSearch;
use App\Models\Node;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

final class NodesIndexTable extends Component
{
    use HighlightSearch;
    use WithPagination;

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function nodes(): LengthAwarePaginator
    {
        return Node::query()
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', "%{$this->search}%")
                        ->orWhere('type', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.nodes.nodes-index-table');
    }
}
