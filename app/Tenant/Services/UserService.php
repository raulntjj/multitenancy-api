<?php

namespace App\Tenant\Services;

use App\Tenant\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService {

    public function findUser(Array $data) {
        return User::where('email', $data['email'])->first();
    }

    public function findById(Int $id) {
        return User::where('id', $id)->first();
    }

    public function createUser(Array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function updateUser(array $data, int $id) {
        $user = User::where('id', $id)->first();
        
        $user->fill([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'password' => $data['password'] ?? false ? Hash::make($data['password']) : $user->password,
        ])->save();

        return $user;
    }
    
    public function deleteUser(Int $id) {
        return User::where('id', $id)->delete();
    }
}