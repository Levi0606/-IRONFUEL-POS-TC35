<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn') === true) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        if (!$this->validate([
            'username' => 'required',
            'password' => 'required'
        ])) {
            return redirect()->to('/login')
                ->withInput()
                ->with('error', 'Enter username and password.');
        }

        $model = new UserModel();

        $user = $model->where(
            'username',
            trim($this->request->getPost('username'))
        )->first();

        if (
            !$user ||
            empty($user['password']) ||
            !password_verify(
                $this->request->getPost('password'),
                $user['password']
            )
        ) {
            return redirect()->to('/login')
                ->withInput()
                ->with('error', 'Invalid username or password.');
        }

        session()->regenerate(true);

        session()->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'],
            'isLoggedIn' => true
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}