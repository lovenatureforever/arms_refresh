<?php

namespace App\Livewire\Shared\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;
    public $password;
    public $remember;

    public function mount()
    {
        $this->email = "";
        $this->password = "";
    }

    #[Layout('layouts.auth')]
    public function render()
    {
        return view('livewire.shared.auth.login');
    }

    public function login()
    {
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            return redirect()->intended('home');
        } else {
            session()->flash('error', 'Email and Password mismatch');
        }
    }
}
