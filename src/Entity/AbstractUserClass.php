<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;

/**
 * Класс пользователя
 *
 * @ORM\Entity(repositoryClass=\JoJoBizzareCoders\DigitalJournal\Repository\UserDoctrineRepository::class)
 * @ORM\Table(name="users",
 *     indexes={
 *          @ORM\Index(name="users_login_unq", columns={"login"}),
 *          @ORM\Index(name="users_surname_idx", columns={"surname"}),
 *          @ORM\Index(name="users_type_idx", columns={"type"})
 *     }
 *  )
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=100)
 * @ORM\DiscriminatorMap(
 *     {
 *     "Teacher" = \JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass::class,
 *     "Parent" = \JoJoBizzareCoders\DigitalJournal\Entity\ParentUserClass::class,
 *     "Student" = \JoJoBizzareCoders\DigitalJournal\Entity\StudentUserClass::class
 *     }
 * )
 */
abstract class AbstractUserClass
{
    /**
     * Id пользователя
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="users_id_seq")
     * @var int
     */
    private int $id;

    /**
     * ФИО пользователя
     *
     * @var Fio
     * @ORM\Embedded(class=\JoJoBizzareCoders\DigitalJournal\ValueObject\Fio::class, columnPrefix=false)
     */
    private Fio $fio;

    /**
     * День Рождения пользователя
     *
     * @var DateTimeImmutable
     * @ORM\Column(name="date_of_birth", type="datetime_immutable")
     */
    private DateTimeImmutable $dateOfBirth;

    /**
     * Номер телефона пользователя
     *
     * @var string
     * @ORM\Column(name="phone", type="string", length=15, nullable=false)
     */
    private string $phone;

    /**
     * Адресс пользователя
     *
     * @var Address
     * @ORM\Embedded(class=\JoJoBizzareCoders\DigitalJournal\ValueObject\Address::class, columnPrefix=false)
     */
    private Address $address;

    /**
     * Логин пользователя
     *
     * @var string
     * @ORM\Column(name="login", type="string", length=255, nullable=false)
     */
    private string $login;

    /**
     * Пароль пользователя
     *
     * @var string
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private string $password;


    /**
     * Конструктор Пользователя
     *
     * @param int $id - Id пользователя
     * @param Fio $fio - ФИО пользователя
     * @param DateTimeImmutable $dateOfBirth - Дата рождения Пользователя
     * @param string $phone - Номер телефона Пользователя
     * @param Address $address - Домашний адресс пользователя
     * @param string $login - логин пользователя
     * @param string $password - пароль пользователя
     */
    public function __construct(
        int $id,
        Fio $fio,
        DateTimeImmutable $dateOfBirth,
        string $phone,
        Address $address,
        string $login,
        string $password
    ) {
        $this->id = $id;
        $this->fio = $fio;
        $this->dateOfBirth = $dateOfBirth;
        $this->phone = $phone;
        $this->address = $address;
        $this->login = $login;
        $this->password = $password;
    }


    /**
     * Получение id
     *
     * @return int
     */
    final public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получение ФИО
     *
     * @return Fio
     */
    public function getFio(): Fio
    {
        return $this->fio;
    }

    /**
     * Получение даты рождения
     *
     * @return DateTimeImmutable
     */
    public function getDateOfBirth(): DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    /**
     * Получение номера телефона
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Получение домашнего адресса
     *
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
