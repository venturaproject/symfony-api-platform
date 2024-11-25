<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Security\AuthUserInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\User\Domain\Event\UserDeletedEvent;

class User extends AggregateRoot implements AuthUserInterface
{
    private Uuid $id;
    private string $username;
    private string $email;
    private string $password;

    /**
     * @var string[] The roles assigned to the user
     */
    private array $roles = [];

    /**
     * @param Uuid $id
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string[] $roles The roles assigned to the user
     */
    public function __construct(Uuid $id, string $username, string $email, string $password, array $roles = [])
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->roles = empty($roles) ? ['ROLE_USER'] : $roles;
    }

    public function getId(): string
    {
        return $this->id->toString();  // Convierte Uuid a string
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUserName(): string
    {
        return $this->username;
    }

    public function setUserName(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return string[] The roles assigned to the user
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    /**
     * @param string[] $roles The roles assigned to the user
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }


    public function delete(): void
    {
        $this->recordEvent(new UserDeletedEvent($this->id));
    }
}

