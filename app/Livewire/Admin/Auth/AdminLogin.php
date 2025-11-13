<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AdminLogin extends Component
{
    #[Validate('required|string|email')]
    public $email = '';
    #[Validate('required')]
    public $password = '';
    #[Validate('nullable')]
    public $remember = false;

    public function save()
    {
        $this->validate();
        if(!Auth::guard('admin')->attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            $this->addError('email', 'Invalid email or password');
            return;
        }
        $this->redirectRoute('admin.dashboard');
    }
    public function render()
    {
        return view('livewire.admin.auth.admin-login');
    }
}
