<?php

declare(strict_types=1);

namespace App\Shared\Domain\Security;

use App\User\Domain\Entity\User;

interface UserPasswordHasherInterface
{
    public function hash(User $user, string $plainPassword): string;
    public function verify(User $user, string $plainPassword): bool;
}