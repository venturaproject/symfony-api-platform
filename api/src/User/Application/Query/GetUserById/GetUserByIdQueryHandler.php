<?php

namespace App\User\Application\Query\GetUserById;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetUserByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(private UserRepositoryInterface $repository) { }

    public function __invoke(GetUserByIdQuery $query): User
    {
        $user = $this->repository->findById($query->id);

        if (!$user) {
            throw new UserNotFoundException();
        }
    
        return $user;
    }
}   