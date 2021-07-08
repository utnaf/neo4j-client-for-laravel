<?php

namespace App\Repository;

use App\Exceptions\UserNotFoundException;
use App\Models\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    /**
     * @throws UserNotFoundException
     */
    public function getById(string $identifier): ?User;

    /**
     * @throws UserNotFoundException
     */
    public function getByIdAndToken(mixed $identifier, string $token): ?User;

    /**
     * @throws UserNotFoundException
     */
    public function retrieveByCredentials(array $credentials): ?User;
}
