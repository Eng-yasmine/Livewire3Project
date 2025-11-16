<?php

namespace App\Livewire\Admin\Skills;

use Livewire\Component;
use App\Models\Skill;
use Livewire\WithPagination;
class SkillsData extends Component
{

    use WithPagination;

    public $search = '';

    protected $listeners = [
        'skillCreated' => '$refresh',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.skills.skills-data', [
            'skills' => Skill::query()
                ->when($this->search !== '', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orderByDesc('id')
                ->paginate(3),
        ]);
    }
}
