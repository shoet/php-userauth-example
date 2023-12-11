<?php

namespace App\Models;

use CodeIgniter\BaseModel;
use CodeIgniter\Model;

class UserModel extends BaseModel
{

    protected $table = "users";

    protected $allowedFields = [
        'name',
        'email',
        'password',
        'created_at',
        'updated_at'
    ];

    public function getUsers(): array
    {
        //return $this->select('name','email','created_at', 'updated_at')->findAll();
        return $this->findAll();
    }

    public function getUserByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    public function registerUser(array $data)
    {
        // TODO password hash
        $this->insert($data);
    }
}
