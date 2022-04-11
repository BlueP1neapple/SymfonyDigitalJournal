<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

    use DateTimeImmutable;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * Класс Родителей
     *
     *
     *
     * @ORM\Entity
     * @ORM\Table(name="parents")
     */
final class ParentUserClass extends AbstractUserClass
{
    /**
     * Место работы родителя
     * @var string
     * @ORM\Column(name="place_of_work", type="string", length=255, nullable=false)
     */
    private string $placeOfWork;

    /**
     * email родителя
     *
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private string $email;

    /**
     * Коллекция детей родителя
     *
     * @var Collection
     * @ORM\ManyToMany (targetEntity=\JoJoBizzareCoders\DigitalJournal\Entity\StudentUserClass::class,
     *     inversedBy="parents")
     * @ORM\JoinTable(
     *     name="students_to_parents",
     *     joinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="student_id", referencedColumnName="id")}
     * )
     */
    private Collection $children;

    /**
     * Конструктор класса Родетелей
     * @inheritdoc
     * @param string $placeOfWork - место работы родителей
     * @param string $email - email родителей
     */
    public function __construct(
        int $id,
        array $fio,
        DateTimeImmutable $dateOfBirth,
        string $phone,
        array $address,
        string $placeOfWork,
        string $email,
        string $login,
        string $password
    ) {
        parent::__construct($id, $fio, $dateOfBirth, $phone, $address, $login, $password);
        $this->placeOfWork = $placeOfWork;
        $this->email = $email;
    }


    /**
     * @return string
     */
    public function getPlaceOfWork(): string
    {
        return $this->placeOfWork;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
