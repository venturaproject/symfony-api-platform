<?php

declare(strict_types=1);

namespace App\Shared\Domain\Security;

interface CurrentUserProviderInterface
{
    public function getRequiredCurrentUser(): AuthUserInterface;
    public function getNullableCurrentUser(): ?AuthUserInterface;
}