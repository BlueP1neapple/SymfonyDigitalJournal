<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

use Doctrine\ORM\Mapping as ORM;
use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;

    /**
     * Класс Предметов
     * @ORM\Entity(repositoryClass=\JoJoBizzareCoders\DigitalJournal\Repository\ItemDoctrineRepository::class)
     * @ORM\Table(name="item",
     *     uniqueConstraints={
     *          @ORM\UniqueConstraint(name="item_name_idx", columns={"name"})
     *     })
     */
class ItemClass
{
    /**
     * @var int id предмета
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue (strategy="SEQUENCE")
     * @ORM\SequenceGenerator (sequenceName="item_id_seq")
     */
    private int $id;

    /**
     *  имя предмета
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private string $name;

    /**
     * @var string Полное название предмета
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private string $description;

    /**
     * Конструктор класса предметов
     * @param int $id - id Предмета
     * @param string $name - Название Предмета
     * @param string $description - Расщифровка названия предмета
     */
    public function __construct(int $id, string $name, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return int получить id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string получить имя предмета
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string получить Полное название предмета
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Метод создания объекта класса Предмет из массива данных об предмете
     * @param array $data - массив данных о предмете
     * @return ItemClass - Объект класса предмет
     */
    public static function createFromArray(array $data): ItemClass
    {
        $requiredFields = [
            'id',
            'name',
            'description'
        ];
        $missingFields = array_diff($requiredFields, array_keys($data));
        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутвуют обязательные элементы: %s', implode(',', $missingFields));
            throw new InvalidDataStructureException($errMsg);
        }
        return new ItemClass(
            $data['id'],
            $data['name'],
            $data['description']
        );
    }
}
