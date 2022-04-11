<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

    use DateTimeImmutable;
    use Doctrine\ORM\Mapping as ORM;
    use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;

    /**
     * Класс Учителя
     * @ORM\Entity
     * @ORM\Table(name="teachers")
     */
class TeacherUserClass extends AbstractUserClass
{
    /**
     *  Предмета
     * @ORM\OneToOne(targetEntity=\JoJoBizzareCoders\DigitalJournal\Entity\ItemClass::class)
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    private ItemClass $item;

    /**
     * @var int Кабинет учителя
     * @ORM\Column(name="cabinet", type="integer", nullable=false)
     */
    private int $cabinet;

    /**
     * @var string Email учителя
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private string $email;

    /**
     * Конструктор класса учителя
     * @inheritdoc
     * @param ItemClass $item - Предмет который ведёт учитель
     * @param int $cabinet - Кабинет учителя
     * @param string $email - email учителя
     */
    public function __construct(
        int $id,
        Fio $fio,
        DateTimeImmutable $dateOfBirth,
        string $phone,
        array $address,
        ItemClass $item,
        int $cabinet,
        string $email,
        string $login,
        string $password
    ) {
        parent::__construct($id, $fio, $dateOfBirth, $phone, $address, $login, $password);
        $this->item = $item;
        $this->cabinet = $cabinet;
        $this->email = $email;
    }

    /**
     * @return int Получить номер кабинета учителя
     */
    public function getCabinet(): int
    {
        return $this->cabinet;
    }

    /**
     * Получить предмет учителя
     * @return ItemClass
     */
    public function getItem(): ItemClass
    {
        return $this->item;
    }



    /**
     * Возвращает email преподавателя
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
