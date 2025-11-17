<?php

namespace App\Livewire\Admin\Skills;

use Livewire\Component;
use App\Models\Skill;

class SkillsUpdate extends Component
{
    public ?int $skillId = null;
    public $name;
    public $percentage;

    protected $listeners = [
        'editSkill' => 'loadSkill',
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'percentage' => 'required|integer|min:1|max:100',
    ];

    public function mount(?int $skillId = null): void
    {
        $this->skillId = $skillId;
    }

    public function loadSkill(int $skillId): void
    {
        $this->skillId = $skillId;
        $skill = Skill::find($skillId);
        if ($skill) {
            $this->name = $skill->name;
            $this->percentage = $skill->percentage;
        }

        // Ask the frontend to open the modal
        $this->dispatch('open-update-modal');
    }

    public function updateSkill()
    {
        $this->validate();
        if ($this->skillId) {
            $skill = Skill::find($this->skillId);
            if ($skill) {
                $skill->update([
                    'name' => $this->name,
                    'percentage' => $this->percentage,
                ]);
            }
        }
        $this->reset(['skillId', 'name', 'percentage']);
        $this->dispatch('close-modal');
        $this->dispatch('skillUpdated');
    }
    public function render()
    {
        return view('livewire.admin.skills.skills-update');
    }
}
