<?php

    namespace JoJoBizzareCoders\DigitalJournal\ValueObject;

    use Doctrine\ORM\Mapping as ORM;

    /**
     * Фио пользователя
     * @ORM\Embeddable
     */
final class Fio
{
    /**
     * Фамилия пользователя
     *
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255, nullable=false)
     */
    private string $surname;

    /**
     * Имя пользователя
     *
     * @var string
     * @ORM\Column(name="name",type="string", length=255, nullable=false)
     */
    private string $name;

    /**
     * Отчество пользователя
     *
     * @var string
     * @ORM\Column(name="patronymic", type="string", length=255, nullable=false)
     */
    private string $patronymic;


    /**
     * Конструктор фио пользователя
     *
     * @param string $surname - фамилия пользователя
     * @param string $name - имя пользователя
     * @param string $patronymic - отчество пользователя
     */
    public function __construct(string $surname, string $name, string $patronymic)
    {
        $this->surname = $surname;
        $this->name = $name;
        $this->patronymic = $patronymic;
    }


    /**
     * возвращает отчество пользователя
     *
     * @return string
     */
    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    /**
     * возвращает фамилию пользователя
     *
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * возвращает имя пользователя
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
