<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TemporaryUser;
use App\Mail\ActivateAccountMail;
use Illuminate\Support\Facades\Mail;

class Register extends Component
{
    public function render()
    {
        return view('livewire.register');
    }
}
