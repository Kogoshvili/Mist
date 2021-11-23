<?php

namespace Mist\Services;

use Mist\Models\User;
use Mist\Repositories\UsersRepository;

class UsersService
{
    public $user;
    public $usersRepository;
    protected static $secret = 'secret';
    protected static $headers = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];

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
     * @return string|null
     */
    public function login($email, $password)
    {
        if ($data = $this->user->getByEmail($email)) {
            if (password_verify($password, $data['passwordHash'])) {
                return $this->generateToken(['email' => $email]);
            }
        }

        return null;
    }

    /**
     * Register user
     *
     * @param array $data user data
     *
     * @return string|null
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

        return $this->generateToken(['email' => $data['email']]);
    }


    /**
     * Generate JWT token
     *
     * @param array $payload payload
     *
     * @return string
     */
    protected function generateToken($payload)
    {
        $payload = array_merge(
            $payload,
            [
                'iat' => time(),
                'exp' => time() + 3600
            ]
        );

        $headers_encoded = base64url_encode(json_encode(self::$headers));
        $payload_encoded = base64url_encode(json_encode($payload));

        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", self::$secret, true);
        $signature_encoded = base64url_encode($signature);

        return "$headers_encoded.$payload_encoded.$signature_encoded";
    }

    /**
     * Validate JWT token
     *
     * @param string $token token
     *
     * @return bool
     */
    public function validateToken($jwt)
    {
        [$base64_url_header, $base64_url_payload, $signature_provided] = explode('.', $jwt);

        $signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, self::$secret, true);
        $base64_url_signature = base64url_encode($signature);

        $is_signature_valid = ($base64_url_signature === $signature_provided);

        $header = json_decode(base64_decode($base64_url_header));
        $payload = json_decode(base64_decode($base64_url_payload));

        $is_token_expired = ($payload?->exp - time()) < 0;

        if ($is_token_expired || !$is_signature_valid) {
            return false;
        }

        return $payload;
    }
}
