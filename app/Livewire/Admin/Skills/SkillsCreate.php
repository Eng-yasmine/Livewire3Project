<?php

namespace App\Livewire\Admin\Skills;

use Livewire\Component;
use Livewire\Attributes\Validate;   
use App\Models\Skill;

class SkillsCreate extends Component
{

    #[Validate('required|string|max:255')]
    public $name;
    #[Validate('required|integer|min:1|max:100')]
    public $percentage;

    public function mount()
    {
       
    }

    public function createSkill()
    {
        $this->validate();
        Skill::create([
            'name' => $this->name,
            'percentage' => $this->percentage,
        ]);

        $this->reset(['name', 'percentage']);

        // close the modal
        $this->dispatch('close-modal'); 
        // Tell the listing component to refresh
        $this->dispatch('skillCreated');
    }
    public function render()
    {
        return view('livewire.admin.skills.skills-create');
    }
}
