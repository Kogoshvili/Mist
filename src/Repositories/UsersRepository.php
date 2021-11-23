<?php

namespace Mist\Repositories;

use Mist\Models\User;

class UsersRepository
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
