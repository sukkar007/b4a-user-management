<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Parse\ParseUser as ParseSDKUser;

class ParseUser implements Authenticatable
{
    protected $parseUser;
    
    public function __construct(ParseSDKUser $parseUser)
    {
        $this->parseUser = $parseUser;
    }
    
    public function getAuthIdentifierName()
    {
        return 'objectId';
    }
    
    public function getAuthIdentifier()
    {
        return $this->parseUser->getObjectId();
    }
    
    public function getAuthPassword()
    {
        return null; // Parse handles password authentication
    }
    
    public function getRememberToken()
    {
        return null;
    }
    
    public function setRememberToken($value)
    {
        // Not implemented for Parse
    }
    
    public function getRememberTokenName()
    {
        return null;
    }
    
    public function getAuthPasswordName()
    {
        return 'password';
    }
    
    // Helper methods to access Parse user data
    public function get($key)
    {
        return $this->parseUser->get($key);
    }
    
    public function getUsername()
    {
        return $this->parseUser->getUsername();
    }
    
    public function getEmail()
    {
        return $this->parseUser->getEmail();
    }
    
    public function getObjectId()
    {
        return $this->parseUser->getObjectId();
    }
    
    public function isAdmin()
    {
        return $this->parseUser->get('role') === 'admin';
    }
    
    // Magic method to access Parse user properties
    public function __get($key)
    {
        return $this->parseUser->get($key);
    }
    
    public function __set($key, $value)
    {
        $this->parseUser->set($key, $value);
    }
}

