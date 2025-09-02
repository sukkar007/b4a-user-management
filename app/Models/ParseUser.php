<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Parse\ParseUser as BaseParseUser;
use Parse\ParseClient;

class ParseUser implements Authenticatable
{
    protected $parseUser;
    protected $attributes = [];

    public function __construct(BaseParseUser $parseUser = null)
    {
        // تهيئة Parse SDK
        if (!ParseClient::getApiUrl()) {
            ParseClient::initialize(
                env('PARSE_APP_ID'),
                env('PARSE_REST_KEY'),
                env('PARSE_MASTER_KEY')
            );
            ParseClient::setServerURL(env('PARSE_SERVER_URL'), '/');
        }

        $this->parseUser = $parseUser ?: new BaseParseUser();
        
        if ($parseUser) {
            $this->attributes = [
                'id' => $parseUser->getObjectId(),
                'username' => $parseUser->get('username'),
                'email' => $parseUser->get('email'),
                'isAdmin' => $parseUser->get('isAdmin') ?: false,
                'role' => $parseUser->get('role') ?: 'user',
            ];
        }
    }

    public static function findByCredentials(array $credentials)
    {
        try {
            $user = BaseParseUser::logIn($credentials['username'], $credentials['password']);
            return new self($user);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function isAdmin()
    {
        return $this->parseUser->get('isAdmin') === true;
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->parseUser->getObjectId();
    }

    public function getAuthPassword()
    {
        return $this->parseUser->get('password');
    }

    public function getRememberToken()
    {
        return $this->parseUser->get('remember_token');
    }

    public function setRememberToken($value)
    {
        $this->parseUser->set('remember_token', $value);
        $this->parseUser->save();
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getUsername()
    {
        return $this->parseUser->get('username');
    }

    public function getEmail()
    {
        return $this->parseUser->get('email');
    }

    public function getFullName()
    {
        return $this->parseUser->get('fullName') ?: $this->parseUser->get('username');
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }
}

