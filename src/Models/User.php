<?php

namespace Mist\Models;

class User extends Model
{
    protected $table = 'users';
    protected $hide = ['passwordHash'];

    /**
     * Get user data from database by email
     *
     * @param string $email email
     *
     * @return array
     */
    public function getByEmail($email)
    {
        $this->where('email', '=', $email);
        return $this->rawData = $this->get()[0] ?? [];
    }

    /**
     * Update user password
     *
     * @param string $password new password
     *
     * @return void
     */
    public function updatePassword($email, $password)
    {
        return $this->update(['passwordHash' => password_hash($password, PASSWORD_DEFAULT)])
            ->where('email', '=', $email)
            ->execute();
    }
}
