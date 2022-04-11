<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

    use DateTimeImmutable;
    use Doctrine\ORM\Mapping as ORM;
    use JoJoBizzareCoders\DigitalJournal\Exception\UnexpectedValueException;

    /**
     *Класс занятий
     *
     * @ORM\Entity(repositoryClass=\JoJoBizzareCoders\DigitalJournal\Repository\LessonDoctrineRepository::class)
     * @ORM\Table(name="lesson")
     */
class LessonClass
{
    /**
     * @var int id урока
     * @ORM\Id
     * @ORM\Column (name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue (strategy="SEQUENCE")
     * @ORM\SequenceGenerator (sequenceName="lesson_id_seq")
     */
    private int $id;

    /**
     * Предмет
     * @var ItemClass
     * @ORM\OneToOne(targetEntity=\JoJoBizzareCoders\DigitalJournal\Entity\ItemClass::class)
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    private ItemClass $item;

    /**
     * @var DateTimeImmutable Дата проведения урока
     * @ORM\Column(name="date", type="datetime_immutable")
     */
    private DateTimeImmutable $date;

    /**
     * @var int Длительность урока
     * @ORM\Column (name="lesson_duration", type="integer", nullable=false)
     */
    private int $lessonDuration;

    /**
     * @var TeacherUserClass Преподаватель
     * @ORM\OneToOne(targetEntity=\JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass::class)
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private TeacherUserClass $teacher;

    /**
     * @var ClassClass Класс
     * @ORM\OneToOne(targetEntity=\JoJoBizzareCoders\DigitalJournal\Entity\ClassClass::class)
     * @ORM\JoinColumn(name="class_id", referencedColumnName="id")
     */
    private ClassClass $class;


    /**
     * Конструктор класса занятий
     * @param int $id - id занятия
     * @param ItemClass $item - Предмет занятия
     * @param DateTimeImmutable $date - дата проведения занятия
     * @param int $lessonDuration - Продолжительность проведения занятия
     * @param TeacherUserClass $teacher - Преподователь проводящий занятие
     * @param ClassClass $class -Класс в котором проводиться занятие
     */
    public function __construct(
        int $id,
        ItemClass $item,
        DateTimeImmutable $date,
        int $lessonDuration,
        TeacherUserClass $teacher,
        ClassClass $class
    ) {
        $this->id = $id;
        $this->item = $item;
        $this->date = $date;
        $this->lessonDuration = $lessonDuration;
        $this->teacher = $teacher;
        $this->class = $class;
    }


    /**
     * @return int Получить id урока
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return ItemClass получить предмет
     */
    public function getItem(): ItemClass
    {
        return $this->item;
    }


    /**
     * @return DateTimeImmutable получить дату проведения урока
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * Метод создания объекта класса занятия из массива данных об занятии
     * @param array $data - массив данных об занятии
     * @return LessonClass - объект класса занятий
     * @throws UnexpectedValueException
     */
    public static function createFromArray(array $data): LessonClass
    {
        $requiredFields = [
          'id',
          'item_id',
          'date',
          'lessonDuration',
          'teacher_id',
          'class_id',
        ];
        $missingFields = array_diff($requiredFields, array_keys($data));
        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутвуют обязательные элементы: %s', implode(',', $missingFields));
            throw new UnexpectedValueException($errMsg);
        }
        $stringDate = $data['date'];
        $lessonDate = DateTimeImmutable::createFromFormat('Y.d.m H:i', $stringDate);
        return new LessonClass(
            $data['id'],
            $data['item_id'],
            $lessonDate,
            $data['lessonDuration'],
            $data['teacher_id'],
            $data['class_id'],
        );
    }

    /**
     * Возвращает время проведения занятий
     *
     * @return int
     */
    public function getLessonDuration(): int
    {
        return $this->lessonDuration;
    }

    /**
     * Возвращает информацию о преподователе
     *
     * @return TeacherUserClass
     */
    public function getTeacher(): TeacherUserClass
    {
        return $this->teacher;
    }

    /**
     * Возвращает информацию о классе
     *
     * @return ClassClass
     */
    public function getClass(): ClassClass
    {
        return $this->class;
    }
}
