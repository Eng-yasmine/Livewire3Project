<?php

namespace App\Livewire\Admin\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
class AdminLogout extends Component
{

    public function logout()
    {
        Auth::guard('admin')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->redirectRoute('admin.login');
    }
    public function render()
    {
        return view('livewire.admin.auth.admin-logout');
    }
}
