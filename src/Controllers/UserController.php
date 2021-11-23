<?php

namespace Mist\Controllers;

use Mist\Core\Request;
use Mist\Services\UsersService;

class UserController extends Controller
{
    public $usersService;

    public function __construct(UsersService $usersService)
    {
        $this->usersService = $usersService;
    }

    public function login(Request $request)
    {
        if ($request->email && $request->password) {
            if ($token = $this->usersService->login($request->email, $request->password)) {
                return $this->json(['token' => $token]);
            }
        }

        return $this->json(['message' => 'Invalid Credentials']);
    }

    public function register(Request $request)
    {
        if ($request->email && $request->password) {
            if ($token = $this->usersService->register([
                'email' => $request->email,
                'password' => $request->password
            ])) {
                return $this->json(['token' => $token]);
            }
        }

        return $this->json(['message' => 'Failed', 'error' => $this->usersService->errors]);
    }

    public function validate(Request $request)
    {
        $res = $this->usersService->validateToken($request->token);
        return $this->json($res);
    }
}
