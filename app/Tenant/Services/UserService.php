<?php

namespace App\Tenant\Services;

use App\Tenant\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService {

    public function findManyUsers() {
        return User::all();
    }

    public function findUser(Array $data) {
        return User::where('email', $data['email'])->first();
    }

    public function findByUser(String $user) {
        return User::where('user', $user)->first();
    }

    public function createUser(Array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function updateUser(array $data, String $user) {
        $user = User::where('user', $user)->first();
        
        $user->fill([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'password' => $data['password'] ?? false ? Hash::make($data['password']) : $user->password,
        ])->save();

        return $user;
    }
    
    public function deleteUser(String $user) {
        return User::where('user', $user)->delete();
    }
}