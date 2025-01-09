<?php

namespace App\Tenant\Services;

use App\Tenant\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService {

    public function findManyUsers() {
        return User::withTrashed()->all();
    }

    public function findUser(Array $data) {
        if($data['email'] ?? false) {
            return User::withTrashed()->where('email', $data['email'])->first();
        }
        return User::withTrashed()->where('user', $data['user'])->first();
    }

    public function findById(Int $id) {
        return User::withTrashed()->withTrashed()->where('id', $id)->first();
    }
    
    public function findByUser(String $user) {
        return User::withTrashed()->where('user', $user)->first();
    }

    public function createUser(Array $data) {
        return User::withTrashed()->create([
            'name' => $data['name'],
            'user' => $data['user'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function updateUser(array $data, String $user) {
        $user = User::withTrashed()->where('user', $user)->firstOrFail();

        $user->fill([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'user' => $data['user'] ?? $user->user,
        ])->save();

        return $user;
    }
    
    public function deleteUser(String $user) {
        return User::where('user', $user)->delete();
    }

    public function syncRolesToUser(array $roles, String $user) {
        $user = User::withTrashed()->where('user', $user)->firstOrFail();
        $user->roles()->sync($roles);
    
        return $user->load('roles');
    }
}