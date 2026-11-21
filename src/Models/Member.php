<?php

namespace App\Models;

class Member extends User 
{
    private string $memberType;

    public function __construct(string $id, string $name, string $email, string $memberType = 'Reguler') 
    {
        parent::__construct($id, $name, $email);
        $this->memberType = $memberType;
    }

    public function register(array $data): string 
    {
        $this->setPassword($data['password']);
        
        return json_encode([
            'status' => 'success',
            'message' => 'Registrasi Member berhasil',
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'member_type' => $this->memberType
            ]
        ]);
    }
}