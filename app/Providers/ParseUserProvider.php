<?php

namespace App\Providers;

use App\Models\ParseUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Parse\ParseUser as ParseSDKUser;
use Parse\ParseException;

class ParseUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        try {
            $query = ParseSDKUser::query();
            $parseUser = $query->get($identifier, true);
            return new ParseUser($parseUser);
        } catch (ParseException $e) {
            return null;
        }
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Not implemented for Parse
    }

    public function retrieveByCredentials(array $credentials)
    {
        try {
            $parseUser = ParseSDKUser::logIn($credentials['username'], $credentials['password']);
            return new ParseUser($parseUser);
        } catch (ParseException $e) {
            return null;
        }
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $user !== null;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        // Parse handles password hashing
    }
}

