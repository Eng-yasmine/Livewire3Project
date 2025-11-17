<?php

namespace App\Livewire\Admin\Skills;

use Livewire\Component;
use App\Models\Skill;   
class SkillsShow extends Component
{
    public ?int $skillId = null;
    public ?Skill $skill = null;

    protected $listeners = [
        'showSkill' => 'open',
    ];

    public function open(int $skillId): void
    {
        $this->skillId = $skillId;
        $this->skill = Skill::find($skillId);
        $this->dispatch('open-show-modal');
    }

    public function render()
    {
        return view('livewire.admin.skills.skills-show');
    }
}
