<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role != 'admin') {
            throw new \Illuminate\Auth\AuthenticationException('ACCESS_DENIED');
        }

        return User::get();
    }
}