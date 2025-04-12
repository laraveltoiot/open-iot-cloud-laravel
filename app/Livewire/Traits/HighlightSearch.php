<?php declare(strict_types=1);

namespace App\Livewire\Traits;

trait HighlightSearch
{
    public string $search = '';

    protected function highlight(string $text): string
    {
        if (! $this->search) {
            return e($text);
        }

        $escapedSearch = preg_quote($this->search, '/');

        return preg_replace(
            "/($escapedSearch)/i",
            '<mark class="bg-yellow-200 text-black">$1</mark>',
            e($text)
        );
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
}
