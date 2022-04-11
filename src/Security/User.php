<?php

namespace JoJoBizzareCoders\DigitalJournal\Security;

use JoJoBizzareCoders\DigitalJournal\Entity\AbstractUserClass as DomenUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class User implements
    UserInterface, PasswordAuthenticatedUserInterface
{

    private DomenUser $user;

    /**
     * @param DomenUser $user
     */
    public function __construct(DomenUser $user)
    {
        $this->user = $user;
    }


    public function getRoles()
    {
        return[
            UserRoleInterface::ROLE_AUTH_USER
        ];
    }

    public function getPassword(): ?string
    {
        return $this->user->getPassword();
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function getUsername()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->user->getLogin();
    }
}