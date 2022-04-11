<?php

namespace JoJoBizzareCoders\DigitalJournal\Security;

use Doctrine\ORM\EntityManagerInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\AbstractUserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{

    private EntityManagerInterface $em;

    private AbstractUserRepositoryInterface $userRepository;

    /**
     * @param AbstractUserRepositoryInterface $userRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(AbstractUserRepositoryInterface $userRepository, \Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }


    /**
     * @inheritDoc
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        $userDomen = $this->userRepository->findUserByLogin($user->getUserIdentifier());
        $this->em->refresh($userDomen);
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function supportsClass(string $class)
    {
        return $class === User::class;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByUsername(string $username)
    {
       return $this->loadUserByIdentifier($username);
    }
    /**
     * @inheritDoc
     */
    public function loadUserByIdentifier(string $identifier)
    {
       $userDomen = $this->userRepository->findUserByLogin($identifier);
       if(null === $userDomen){
           throw new UserNotFoundException('Нет такого пользователя');
       }
       return new User($userDomen);
    }


}