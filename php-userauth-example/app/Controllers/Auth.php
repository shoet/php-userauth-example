<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use \Config\Services;
use Predis\Client;
use Ramsey\Uuid\Uuid;

class Auth extends BaseController
{
    public function login(): string
    {
        $data = [
            'title' => 'ログイン'
        ];
        $session_id = $this->request->getCookie('session_id');
        log_message('debug', $session_id ?? "");
        if ($session_id) {
            return redirect()->to('/login')->with('success', 'ログインしました。');
        }
        return view('templates/header', $data).
            view('auth/login', $data).
            view('templates/footer');
    }

    public function signin()
    {
        // TODO: setcookie
        $data = [
            'title' => 'ログイン'
        ];
        $validation = Services::validation();
        $validation->setRules([
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validation->withRequest($this->request)->run()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $model = model(UserModel::class);
            $user = $model->getUserByEmail($email);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $session = session();
                    $session->set('user_id', $user['id']);
                    $session->set('username', $user['name']);
                    $session->set('email', $user['email']);
                    $session->set('logged_in', true);
                    $uuid = $this->generateSession($email);
                    $this->response->setCookie([
                        'name' => 'session_id',
                        'domain' => 'localhost',
                        'value' => $uuid,
                        'expire' => 86500,
                        'path' => '/',
                        'secure' => FALSE,
                        'httponly' => FALSE,
                        'samesite' => 'lax',
                    ]);
                    return redirect()->to('/login')->with('success', 'ログインしました。');
                } else {
                    log_message('debug', 'signin() validation failed');
                    session()->setFlashdata('errors', ['メールアドレスかパスワードが間違っています。']);
                    return view('templates/header', $data).
                        view('auth/login', $data).
                        view('templates/footer');
                }
            } else {
                log_message('debug', 'signin() validation failed');
                session()->setFlashdata('errors', ['メールアドレスかパスワードが間違っています。']);
                return view('templates/header', $data).
                    view('auth/login', $data).
                    view('templates/footer');
            }
            return view('templates/header', ['title' => 'Login']).
                view('auth/login_success').
                view('templates/footer');
        } else {
            log_message('debug', 'signup() validation failed');
            $errors = $validation->getErrors();
            session()->setFlashdata('errors', $errors);
            return view('templates/header', $data).
                view('auth/login', $data).
                view('templates/footer');
        }
        return view('templates/header', $data).
            view('auth/login', $data).
            view('templates/footer');
    }

    public function register(): string
    {
        $data = [
            'title' => 'ユーザー登録'
        ];

        return view('templates/header', $data).
            view('auth/register', $data).
            view('templates/footer');
    }

    public function signup()
    {
        $data = [
            'title' => 'ユーザー登録'
        ];
        $validation = Services::validation();
        $validation->setRules([
            'username' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
        ]);

        if ($validation->withRequest($this->request)->run()) {
            $username = $this->request->getPost('username');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $model = model(UserModel::class);
            $model->registerUser($username, $email, $password_hashed);

            log_message('debug', 'signup() called');
            return view('templates/header', ['title' => 'Login']).
                view('auth/register_validate').
                view('templates/footer');
        } else {
            log_message('debug', 'signup() validation failed');
            $errors = $validation->getErrors();
            session()->setFlashdata('errors', $errors);
            return view('templates/header', $data).
                view('auth/register', $data).
                view('templates/footer');
        }
    }

    function generateSession($email): string
    {
        $uuid = Uuid::uuid4()->toString();
        $client = new Client([
            'scheme' => 'tcp',
            'host' => 'kvs',
            'port' => 6379,
        ]);
        $client->set($uuid, $email);
        return $uuid;
    }

    function validateSession($session_id): bool
    {
        $client = new Client([
            'scheme' => 'tcp',
            'host' => 'kvs',
            'port' => 6379,
        ]);
        $email = $client->get($session_id);
        log_message('debug', 'validateSession() called');
        log_message('debug', $email);
        if ($email) {
            return $email;
        } else {
            return false;
        }
    }
}
