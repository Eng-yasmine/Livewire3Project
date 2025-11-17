<?php

namespace App\Livewire\Admin\Skills;

use Livewire\Component;
use App\Models\Skill;

class SkillsDelete extends Component
{

    public ?int $skillId = null;
// listen for the confirm delete event
    protected $listeners = [
        'confirmDelete' => 'open',
    ];
// open the delete modal
    public function open(int $skillId): void
    {
        $this->skillId = $skillId;
        $this->dispatch('open-delete-modal');
    }
// delete the skill
    public function deleteSkill()
    {
        if ($this->skillId) {
            $skill = Skill::find($this->skillId);
            if ($skill) {
                $skill->delete();
            }
        }
        $this->reset(['skillId']);
        $this->dispatch('close-modal');
        $this->dispatch('skillUpdated');
    }
// render the delete modal
    public function render()
    {
        return view('livewire.admin.skills.skills-delete');
    }
}
