<?php

namespace App\Auth;

use App\Models\User;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class UsernameUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials (username).
     */
    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        if (empty($credentials) || !isset($credentials['username'])) {
            return null;
        }

        return $this->createModel()
            ->newQuery()
            ->where('username', $credentials['username'])
            ->first();
    }

    /**
     * Validate a user against the given credentials.
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return $this->hasher->check(
            $credentials['password'],
            $user->getAuthPassword()
        );
    }
}
