<?php

namespace JoJoBizzareCoders\DigitalJournal\Security;

/**
 * Роли пользователя
 */
interface UserRoleInterface
{
    /**
     * Роль аутентифицированного пользователя
     */
    public const ROLE_AUTH_USER = 'ROLE_AUTH_USER';
}