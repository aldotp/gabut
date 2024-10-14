<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService {

    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function getUser($id)
    {
        return $this->userRepository->getUser($id);
    }

    public function createUser($data)
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user) {
            return null;
        }

        return $this->userRepository->createUser([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role_id' => 2,
        ]);
    }

    public function updateUser($id, $data) {
        return $this->userRepository->updateUser($id, $data);
    }

    public function deleteUser($id) {
        $user = $this->userRepository->getUser($id);

        if (!$user) {
            return null;
        }

        return $this->userRepository->deleteUser($id);
    }


}