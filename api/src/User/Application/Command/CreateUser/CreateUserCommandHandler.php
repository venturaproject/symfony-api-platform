<?php

namespace App\User\Application\Command\CreateUser;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\User\Domain\Factory\UserFactory;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Entity\User;

class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private UserFactory $userFactory
    ) { }

    /**
     * @return User The created user.
     */
    public function __invoke(CreateUserCommand $command): User
    {
        $roles = empty($command->roles) ? ['ROLE_USER'] : $command->roles;
        $user = $this->userFactory->create(
            $command->username, 
            $command->email, 
            $command->password,
            $roles 
        );

        $this->repository->save($user);

        return $user;
    }
}
