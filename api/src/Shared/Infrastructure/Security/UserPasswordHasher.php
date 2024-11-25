<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Security;

use App\Shared\Domain\Security\UserPasswordHasherInterface;
use App\User\Domain\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as SymfonyUserPasswordHasherInterface;

class UserPasswordHasher implements UserPasswordHasherInterface
{
    public function __construct(private SymfonyUserPasswordHasherInterface $symfonyUserPasswordHasher)
    {
    }

    public function hash(User $user, string $plainPassword): string
    {
        return $this->symfonyUserPasswordHasher->hashPassword($user, $plainPassword);
    }

    public function verify(User $user, string $plainPassword): bool
    {
        return $this->symfonyUserPasswordHasher->isPasswordValid($user, $plainPassword);
    }
}