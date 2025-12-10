<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TestLoginController
{
    public function testLogin()
    {
        $user = User::where('email', 'admin@admin.com')->first();

        if ($user) {
            Auth::login($user);
            return redirect('/admin');
        }

        return 'User not found';
    }
}
