<?php

namespace App\Providers;

use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Support\Facades\Hash;

class Neo4jUserProvider implements UserProvider
{
    /**
     * The User repository implementation
     *
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * The hasher implementation.
     *
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * Neo4jUserProvider constructor.
     * @param UserRepository $userRepository
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     */
    public function __construct(UserRepository $userRepository, HasherContract $hasher)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    public function retrieveById($identifier)
    {
        try {
            return $this->userRepository->getById($identifier);
        } catch (UserNotFoundException $e) {
            return null;
        }
    }

    public function retrieveByToken($identifier, $token)
    {
        try {
            return $this->userRepository->getByIdAndToken($identifier, $token);
        } catch (UserNotFoundException $e) {
            return null;
        }
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        /** @var User $user  */
        $user->setRememberToken($token);
        $this->userRepository->save($user);
    }

    public function retrieveByCredentials(array $credentials)
    {
        try {
            return $this->userRepository->retrieveByCredentials($credentials);
        } catch (UserNotFoundException $e) {
            return null;
        }
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $this->hasher->check(
            $credentials['password'], $user->getAuthPassword()
        );
    }
}
