<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUser($id)
    {
        return User::find($id);
    }

    public function createUser($data)
    {
        return User::create($data);
    }

    public function updateUser($id, $data)
    {
        $user = User::find($id);
        $user->update($data);
        return $user;
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
        return $user;
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

}
