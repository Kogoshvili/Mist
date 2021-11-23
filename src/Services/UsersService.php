<?php

namespace Mist\Services;

use Mist\Models\User;
use Mist\Repositories\UsersRepository;

class UsersService
{
    public $user;
    public $usersRepository;

    public function __construct(User $user, UsersRepository $usersRepository)
    {
        $this->user = $user;
        $this->usersRepository = $usersRepository;
    }

    /**
     * Authorize user
     *
     * @param string $email email
     * @param string $password password
     *
     * @return User|null
     */
    public function login($email, $password)
    {
        if ($data = $this->user->getByEmail($email)) {
            if (password_verify($password, $data['passwordHash'])) {
                return $this->user;
            }
        }

        return null;
    }

    /**
     * Register user
     *
     * @param array $data user data
     *
     * @return User|null
     */
    public function register($data)
    {
        if (empty($data['email']) || empty($data['password'])) {
            $this->errors[] = ['Email and Password is required'];
            return null;
        }

        try {
            $this->user->insert([
                'email' => $data['email'],
                'passwordHash' => password_hash($data['password'], PASSWORD_DEFAULT)
            ]);
            $this->user->execute();
        } catch (\Exception $e) {
            if ($e->getCode() === '23000') {
                $this->errors[] = ['Email already registered'];
                return null;
            }

            $this->errors[] = [$e->getMessage()];
            return null;
        }

        return true;
    }
}
