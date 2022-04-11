<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

    use DateTimeImmutable;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * Класс Студента
     *
     *
     * @ORM\Entity
     * @ORM\Table(
     *     name="students"
     *     )
     */
class StudentUserClass extends AbstractUserClass
{
    /**
     *  класс ученика
     * @ORM\OneToOne(targetEntity=\JoJoBizzareCoders\DigitalJournal\Entity\ClassClass::class)
     * @ORM\JoinColumn(name="class_id", referencedColumnName="id")
     */
    private ClassClass $class;

    /**
     * Родитель ученика
     * @ORM\ManyToMany(targetEntity=\JoJoBizzareCoders\DigitalJournal\Entity\ParentUserClass::class, mappedBy="children")
     */
    private Collection $parents;


    /**
     * Конструктор класса студента
     * @inheritdoc
     * @param ClassClass $class - объект класса класса
     * @param ParentUserClass[] $parents - объект класса родетелец
     */
    public function __construct(
        int $id,
        array $fio,
        DateTimeImmutable $dateOfBirth,
        string $phone,
        array $address,
        ClassClass $class,
        array $parents,
        string $login,
        string $password
    ) {
        parent::__construct($id, $fio, $dateOfBirth, $phone, $address, $login, $password);
        $this->class = $class;
        $this->parents = new ArrayCollection($parents);
    }

    /**
     * Получить в каком классе ученик
     * @return ClassClass
     */
    public function getClass(): ClassClass
    {
        return $this->class;
    }

    /**
     * Получить Родителя
     * @return ParentUserClass[]
     */
    public function getParents(): array
    {
        return $this->parents->toArray();
    }
}
