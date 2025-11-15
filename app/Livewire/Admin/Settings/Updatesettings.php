<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use Livewire\Component;

class Updatesettings extends Component
{

    public Setting $settings;

    public function mount()
    {
        $this->settings = Setting::first();
    }

    public function rules()
    {
        return [
            'settings.name' => 'required|string|max:255',
            'settings.email' => 'required|email|max:255',
            'settings.phone' => 'required|string|max:255',
            'settings.address' => 'required|string|max:255',
            'settings.facebook' => 'nullable|url|max:255',
            'settings.twitter' => 'nullable|url|max:255',
            'settings.instagram' => 'nullable|url|max:255',
            'settings.linkedin' => 'nullable|url|max:255',
        ];
    }   

    public function updateSettings()
    {
        $this->validate();
        $this->settings->save();
        $this->dispatch('settings-updated');

        session()->flash('message', 'Settings updated successfully');
    }

    public function render()
    {
        return view('livewire.admin.settings.updatesettings');
    }
}
