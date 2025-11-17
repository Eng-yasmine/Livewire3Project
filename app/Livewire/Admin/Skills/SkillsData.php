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
        'skillUpdated' => '$refresh',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function editSkill(int $id): void
    {
        $this->dispatch('editSkill', skillId: $id)->to(SkillsUpdate::class);
    }

    public function deleteSkill(int $id): void
    {
        Skill::where('id', $id)->delete();
        $this->resetPage();
        $this->dispatch('skillUpdated');
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('confirmDelete', skillId: $id)->to(SkillsDelete::class);
    }

    public function showSkill(int $id): void
    {
        $this->dispatch('showSkill', skillId: $id)->to(SkillsShow::class);
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
