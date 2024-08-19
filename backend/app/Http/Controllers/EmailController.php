<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class EmailController extends Controller
{
    
    public function sendPasswordChange(Request $request) {
        $validated = $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $validated 
        );   

        return $status;
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->password = Hash::make($password);
                $user->save();
    
            }
        );

        return $status;
    }


}
