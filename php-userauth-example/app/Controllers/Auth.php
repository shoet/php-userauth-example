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
        $model = model(UserModel::class);
        $model->registerUser($data);

        log_message('debug', 'signup() called');
        // TODO ユーザ登録
        return view('templates/header', $data).
            view('auth/login', $data).
            view('templates/footer');
    }

    public function login(): string
    {

    }
}
