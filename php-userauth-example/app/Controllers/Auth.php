<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Login'
        ];
        return view('templates/header', $data).
            view('auth/login', $data).
            view('templates/footer');
    }

    public function register(): string
    {
        $data = [
            'title' => 'ユーザー登録'
        ];
        $model = model(UserModel::class);
        $user = $model->getUserByEmail($this->request->getPost('email'));

        return view('templates/header', $data).
            view('auth/register', $data).
            view('templates/footer');
    }

    public function signup(): string
    {
        $data = [
            'title' => 'ユーザー登録'
        ];
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $model = model(UserModel::class);
        $model->registerUser($username, $email, $password_hashed);

        log_message('debug', 'signup() called');
        return view('templates/header', ['title' => 'Login']).
            view('auth/login', $data).
            view('templates/footer');
    }

    public function login(): string
    {

    }
}
