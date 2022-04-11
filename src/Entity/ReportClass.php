<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use JoJoBizzareCoders\DigitalJournal\Exception\UnexpectedValueException;

    /**
     * Класс оценок
     * @ORM\Entity(repositoryClass=\JoJoBizzareCoders\DigitalJournal\Repository\AssessmentReportDoctrineRepository::class)
     * @ORM\Table(name="assessment_report")
     */
class ReportClass
{
    /**
     * id оценки
     * @var int
     * @ORM\Id
     * @ORM\Column (name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue (strategy="SEQUENCE")
     * @ORM\SequenceGenerator (sequenceName="assessment_report_id_seq")
     */
    private int $id;

    /**
     * @var LessonClass Урок на котором получена оценка
     * @ORM\OneToOne(targetEntity=\JoJoBizzareCoders\DigitalJournal\Entity\LessonClass::class)
     * @ORM\JoinColumn(name="lesson_id", referencedColumnName="id")
     */
    private LessonClass $lesson;

    /**
     * @var StudentUserClass Ученик
     * @ORM\OneToOne(targetEntity=\JoJoBizzareCoders\DigitalJournal\Entity\StudentUserClass::class)
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private StudentUserClass $student;

    /**
     * @var int Оценка
     * @ORM\Column(name="mark",type="integer", nullable=false)
     */
    private int $mark;

    /**
     * Конструкор класса оценок
     * @param int $id - id оценок
     * @param LessonClass $lesson - Занятие в котором поставили оценку
     * @param StudentUserClass $student - студент которому поставили оценку
     * @param int $mark - значение оценки
     */
    public function __construct(int $id, LessonClass $lesson, StudentUserClass $student, int $mark)
    {
        $this->id = $id;
        $this->lesson = $lesson;
        $this->student = $student;
        $this->mark = $mark;
    }

    /**
     * Метод создания объекта класса Оценок из массива данных об оценках
     * @param array $data - массив данных об оценках
     * @return ReportClass - объект класса оценок
     */
    public static function createFromArray(array $data): ReportClass
    {
        $requiredFields = [
            'id',
            'lesson_id',
            'student_id',
            'mark'
        ];
        $missingFields = array_diff($requiredFields, array_keys($data));
        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутвуют обязательные элементы: %s', implode(',', $missingFields));
            throw new UnexpectedValueException($errMsg);
        }
        return new ReportClass(
            $data['id'],
            $data['lesson_id'],
            $data['student_id'],
            $data['mark']
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return LessonClass
     */
    public function getLesson(): LessonClass
    {
        return $this->lesson;
    }

    /**
     * @return StudentUserClass
     */
    public function getStudent(): StudentUserClass
    {
        return $this->student;
    }

    /**
     * @return int
     */
    public function getMark(): int
    {
        return $this->mark;
    }
}
