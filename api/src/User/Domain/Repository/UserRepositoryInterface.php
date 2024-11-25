<?php

namespace App\User\Domain\Repository;

use App\Shared\Domain\ValueObject\Uuid;
use App\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function remove(User $user): void;
    public function findById(Uuid $id): ?User;
    public function findByEmail(string $email): ?User;
    
    /**
     * @return User[] An array of User entities
     */
    public function findAll(): array;
}
