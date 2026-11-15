<?php

namespace App\Models;

class User 
{
    protected string $id;
    protected string $name;
    protected string $email;
    
    private string $password;

    public function __construct(string $id, string $name, string $email) 
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function setPassword(string $rawPassword): void 
    {
        $this->password = password_hash($rawPassword, PASSWORD_BCRYPT);
    }

    public function getPassword(): string 
    {
        return $this->password;
    }

    public function register(array $data): string 
    {
        $this->setPassword($data['password']);
        
        return json_encode([
            'status' => 'success',
            'message' => 'Registrasi User berhasil',
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email
            ]
        ]);
    }
}