<?php

namespace App\Controllers;

use App\Helpers\JsonStorage;
use App\Models\Member;

class AuthController
{
    private JsonStorage $storage;

    public function __construct(JsonStorage $storage)
    {
        $this->storage = $storage;
    }

    public function register(array $input): string
    {
        $name = trim($input['nama'] ?? $input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $memberType = $input['member_type'] ?? 'Reguler';

        if ($name === '' || $email === '' || $password === '') {
            http_response_code(400);
            return json_encode([
                'status' => 'error',
                'message' => 'Nama, email, dan kata sandi wajib diisi.'
            ]);
        }

        $users = $this->storage->readAll();
        foreach ($users as $existing) {
            if (strcasecmp($existing['email'], $email) === 0) {
                http_response_code(409);
                return json_encode([
                    'status' => 'error',
                    'message' => 'Email sudah terdaftar. Silakan gunakan email lain.'
                ]);
            }
        }

        $newId = 'M' . str_pad((string)(count($users) + 1), 3, '0', STR_PAD_LEFT);
        $member = new Member($newId, $name, $email, $memberType);
        $result = json_decode($member->register(['password' => $password]), true);

        $this->storage->append([
            'id' => $newId,
            'name' => $name,
            'email' => $email,
            'password' => $member->getPassword(),
            'member_type' => $memberType
        ]);

        return json_encode($result);
    }

    public function login(array $input): string
    {
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if ($email === '' || $password === '') {
            http_response_code(400);
            return json_encode([
                'status' => 'error',
                'message' => 'Email dan kata sandi wajib diisi.'
            ]);
        }

        $users = $this->storage->readAll();
        foreach ($users as $user) {
            if (strcasecmp($user['email'], $email) === 0 && password_verify($password, $user['password'])) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Login berhasil. Selamat datang, ' . $user['name'] . '!',
                    'data' => [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'member_type' => $user['member_type'] ?? 'Reguler'
                    ]
                ]);
            }
        }

        http_response_code(401);
        return json_encode([
            'status' => 'error',
            'message' => 'Email atau kata sandi salah.'
        ]);
    }
}