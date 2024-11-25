<?php

namespace App\User\Domain\Service;

use App\Shared\Domain\ValueObject\Uuid;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;

/**
 * This class is responsible for managing the user profile.
 */
class UserProfileService 
{
    public function __construct(private UserRepositoryInterface $userRepository) { }

    public function changeUsername(Uuid $id, string $newUserName): User
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->setUserName($newUserName);

        $this->userRepository->save($user);

        return $user;
    }

    // Other methods like changeEmail ...
}