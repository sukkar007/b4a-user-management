<?php

namespace App\Providers;

use App\Models\ParseUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class ParseUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        try {
            $parseUser = \Parse\ParseUser::query()
                ->equalTo('objectId', $identifier)
                ->first();
            
            return $parseUser ? new ParseUser($parseUser) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function retrieveByToken($identifier, $token)
    {
        try {
            $parseUser = \Parse\ParseUser::query()
                ->equalTo('objectId', $identifier)
                ->equalTo('remember_token', $token)
                ->first();
            
            return $parseUser ? new ParseUser($parseUser) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // تحديث رمز التذكر في Parse
        if ($user instanceof ParseUser) {
            $user->setRememberToken($token);
        }
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) || !isset($credentials['username']) || !isset($credentials['password'])) {
            return null;
        }

        return ParseUser::findByCredentials($credentials);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // تم التحقق بالفعل في retrieveByCredentials
        return true;
    }
}

