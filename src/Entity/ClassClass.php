<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;

    /**
     * Класс классов
     * @ORM\Entity(repositoryClass=\JoJoBizzareCoders\DigitalJournal\Repository\ClassDoctrineRepository::class)
     * @ORM\Table(name="class")
     */
class ClassClass
{
    /**
     * @var int id класса
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue (strategy="SEQUENCE")
     * @ORM\SequenceGenerator (sequenceName="class_id_seq")
     */
    private int $id;

    /**
     * @var int Номер класса
     * @ORM\Column(name="number",type="integer", nullable=false)
     */
    private int $number;

    /**
     * @var string Буква класса
     * @ORM\Column(name="letter",type="string", length=1, nullable=false)
     */
    private string $letter;


    /**
     * Конструтор классов
     * @param int $id
     * @param int $number
     * @param string $letter
     */
    public function __construct(int $id, int $number, string $letter)
    {
        $this->id = $id;
        $this->number = $number;
        $this->letter = $letter;
    }

    /**
     * @return int получить id класса
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int получить Номер класса
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return string получить Букву класса
     */
    public function getLetter(): string
    {
        return $this->letter;
    }

    /**
     * Метод создания объекта класса классов из масиива данных о классах
     * @param array $data - массив данных о классах
     * @return ClassClass - Объект класса классов
     */
    public static function createFromArray(array $data): ClassClass
    {
        $requiredFields = [
            'id',
            'number',
            'letter'
        ];
        $missingFields = array_diff($requiredFields, array_keys($data));
        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутвуют обязательные элементы: %s', implode(',', $missingFields));
            throw new RuntimeException($errMsg);
        }
        return new ClassClass(
            $data['id'],
            $data['number'],
            $data['letter'],
        );
    }
}
