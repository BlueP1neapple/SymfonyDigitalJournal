<?php

namespace JoJoBizzareCoders\DigitalJournal\Service;

use JoJoBizzareCoders\DigitalJournal\Entity\AbstractUserRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\ClassRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\NewLessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\ResultRegistrationLessonDto;

/**
 * Сервис регистрации нового урока
 */
class NewLessonService
{
    /**
     * Репозиторий уроков
     *
     * @var LessonRepositoryInterface
     */
    private LessonRepositoryInterface $lessonRepository;

    /**
     * Репозиторий учетелей
     *
     * @var TeacherRepositoryInterface
     */
    private TeacherRepositoryInterface $teacherRepository;

    /**
     * Репозиторий предметов
     *
     * @var ItemRepositoryInterface
     */
    private ItemRepositoryInterface $itemRepository;

    /**
     * Репозиторий классов
     *
     * @var ClassRepositoryInterface
     */
    private ClassRepositoryInterface $classRepository;

    /**
     * @param LessonRepositoryInterface $lessonRepository
     * @param TeacherRepositoryInterface $teacherRepository
     * @param ItemRepositoryInterface $itemRepository
     * @param ClassRepositoryInterface $classRepository
     */
    public function __construct(
        LessonRepositoryInterface $lessonRepository,
        TeacherRepositoryInterface $teacherRepository,
        ItemRepositoryInterface $itemRepository,
        ClassRepositoryInterface $classRepository
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->teacherRepository = $teacherRepository;
        $this->itemRepository = $itemRepository;
        $this->classRepository = $classRepository;
    }

    public function registerLesson(NewLessonDto $lessonDto): ResultRegistrationLessonDto
    {
        $teacherId = $lessonDto->getTeacherId();
        $itemId = $lessonDto->getItemId();
        $classId = $lessonDto->getClassId();

        $teacherCollection = $this->teacherRepository->findBy(['id' => $teacherId]);
        $itemCollection = $this->itemRepository->findBy(['id' => $itemId]);
        $classCollection = $this->classRepository->findBy(['id' => $classId]);

        if (1 !== count($teacherCollection)) {
            throw new RuntimeException("Нельзя зарегестрировать урок с преподом = '$teacherId'");
        }
        $teacher = current($teacherCollection);

        if (1 !== count($itemCollection)) {
            throw new RuntimeException("Нельзя зарегестрировать предмет = '$itemId'");
        }
        $item = current($itemCollection);

        if (1 !== count($classCollection)) {
            throw new RuntimeException("Нельзя зарегестрировать класс = '$classId'");
        }
        $class = current($classCollection);


        $date = \DateTimeImmutable::createFromFormat('Y.m.d G:i', $lessonDto->getDate());

        $entity = new LessonClass(
            $this->lessonRepository->nextId(),
            $item,
            $date,
            $lessonDto->getLessonDuration(),
            $teacher,
            $class
        );
        $this->lessonRepository->add($entity);

        return new ResultRegistrationLessonDto(
            $entity->getId()
        );
    }
}
