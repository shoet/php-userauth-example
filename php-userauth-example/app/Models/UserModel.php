<?php

namespace App\Models;

use CodeIgniter\BaseModel;
use CodeIgniter\Model;

class UserModel extends Model
{

    protected $table = 'users';

    protected $allowedFields = [
        'name',
        'email',
        'password',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getUsers(): array
    {
        //return $this->select('name','email','created_at', 'updated_at')->findAll();
        return $this->findAll();
    }

    public function getUserByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    public function registerUser(string $username, string $email, string $password)
    {
        $data = [
            'name' => $username, 
            'email' => $email,
            'password' => $password
        ];
        $this->insert($data);
    }
}
