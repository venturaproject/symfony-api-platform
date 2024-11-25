<?php

namespace App\User\Domain\Service;

use App\Shared\Domain\Security\UserPasswordHasherInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;

/**
 * This class is responsible for managing the user password.
 */
class UserPasswordService
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepositoryInterface $userRepository
    ) { }

    /**
     * Change the user password.
     * 
     * @param Uuid $id - The user id
     * @param string $newPassword - The new password
     * @return User - The updated user
     * @throws UserNotFoundException - If the user is not found
     */    
    public function changePassword(Uuid $id, string $newPassword): User
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->setPassword($this->passwordHasher->hash($user, $newPassword));

        $this->userRepository->save($user);

        return $user;
    }

    public function isPasswordValid(User $user, string $currentPassword): bool
    {
        return password_verify($currentPassword, $user->getPassword());
    }
}
