<?php

namespace App\Core\Services;

use App\Core\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService {

    public function findUser(Array $data) {
        return User::where('email', $data['email'])->first();
    }

    public function createUser(Array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function deleteUser(Int $id) {
        return User::where('id', $id)->delete();
    }
}