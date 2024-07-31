<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Login',
            'config' => config('Auth'),
        ];

        return view('auth/login', $data);
    }
}
