<?php

namespace App\Livewire\Conductor;

use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    #[Url(as: 'q')] public string $search = '';
    #[Url] public string $type = 'all';
    #[Url] public string $owner = 'me';
    #[Url] public string $sort = 'modified_desc';

    public function updating($name, $value)
    {
        if (in_array($name, ['type','owner','sort'])) $this->resetPage();
    }

    protected function dataset(): Collection
    {
        // Simulación de datos (cámbialo por tu modelo/DB)
        return collect([
            [
                'name' => 'Bakehouse Sales Starter Space',
                'type' => 'genie',
                'owner' => 'bryanperaltahu@gmail.com',
                'created_at' => 'Aug 06, 2025, 05:49 PM',
                'modified_at' => now()->subMinutes(3)->toDateTimeString(),
                'icon' => 'space',
                'starred' => false,
            ],
            [
                'name' => 'Untitled Notebook 2025-08-06 18:00:34',
                'type' => 'notebook',
                'owner' => 'bryanperaltahu@gmail.com',
                'created_at' => 'Aug 06, 2025, 06:00 PM',
                'modified_at' => now()->subHour()->toDateTimeString(),
                'icon' => 'notebook',
                'starred' => false,
            ],
            [
                'name' => 'Workspace Usage Dashboard',
                'type' => 'dashboard',
                'owner' => 'bryanperaltahu@gmail.com',
                'created_at' => 'Aug 06, 2025, 05:49 PM',
                'modified_at' => now()->subDays(1)->toDateTimeString(),
                'icon' => 'dashboard',
                'starred' => true,
            ],
        ]);
    }

    public function getItemsProperty(): Collection
    {
        $items = $this->dataset();

        if ($this->search !== '') {
            $q = mb_strtolower($this->search);
            $items = $items->filter(fn($i) => str_contains(mb_strtolower($i['name']), $q));
        }

        if ($this->type !== 'all') {
            $items = $items->where('type', $this->type);
        }

        if ($this->owner === 'me') {
            // Ajusta si tienes multi-usuario
            $items = $items->where('owner', auth()->user()->email ?? '');
        }

        $items = match ($this->sort) {
            'modified_asc'  => $items->sortBy('modified_at'),
            'name_asc'      => $items->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE),
            'name_desc'     => $items->sortByDesc('name', SORT_NATURAL | SORT_FLAG_CASE),
            default         => $items->sortByDesc('modified_at'), // modified_desc
        };

        return $items->values();
    }

    public function render()
    {
        return view('livewire.conductor.v-conductor');
    }
}
