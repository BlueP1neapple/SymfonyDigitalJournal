<?php

namespace JoJoBizzareCoders\DigitalJournal\Service;

use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonRepositoryInterface;
use Psr\Log\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\AddressDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\ClassDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\FioDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\ItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\LessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\SearchLessonServiceCriteria;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\TeacherDto;

/**
 * Сервис поиска занятий
 */
class SearchLessonService
{
    /**
     * Используемый логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Репризиторий для работы с занятиями
     *
     * @var LessonRepositoryInterface
     */
    private LessonRepositoryInterface $lessonRepository;

    /**
     * Конструктор Сервиса поиска занятий
     *
     * @param LoggerInterface $logger - используемый логгер
     * @param LessonRepositoryInterface $lessonRepository - Репризиторий для работы с занятиями
     */
    public function __construct(
        LoggerInterface $logger,
        LessonRepositoryInterface $lessonRepository
    ) {
        $this->logger = $logger;
        $this->lessonRepository = $lessonRepository;
    }

    /**
     * Метод осузевствления поиска занятий по критериям
     *
     * @param SearchLessonServiceCriteria $searchCriteria - критериии поиска занятий
     * @return array
     */
    public function search(SearchLessonServiceCriteria $searchCriteria): array
    {
        $criteria = $this->searchCriteriaForArray($searchCriteria);
        if (array_key_exists('id', $criteria)) {
            $criteria['lesson_id'] = $criteria['id'];
            unset($criteria['id']);
        }
        $entitiesCollection = $this->lessonRepository->findBy($criteria);
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->info('found lesson: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    /**
     * Создание dto объекта с информацией об занятиях
     *
     * @param LessonClass $lesson - коллекция найденных по критериям занятий
     * @return LessonDto
     */
    private function createDto(LessonClass $lesson): LessonDto
    {
        $item = $lesson->getItem();
        $itemDto = new ItemDto(
            $item->getId(),
            $item->getName(),
            $item->getDescription()
        );
        $teacher = $lesson->getTeacher();
        $teacherAddress = $teacher->getAddress();
        $teacherAddressDto = new AddressDto(
            $teacherAddress->getStreet(),
            $teacherAddress->getHome(),
            $teacherAddress->getApartment()
        );
        $teacherDto = new TeacherDto(
            $teacher->getId(),
            [
                'surname'    => $teacher->getFio()->getSurname(),
                'name'       => $teacher->getFio()->getName(),
                'patronymic' => $teacher->getFio()->getPatronymic()],
            $teacher->getDateOfBirth()->format('Y.m.d'),
            $teacher->getPhone(),
            $teacherAddressDto,
            $itemDto,
            $teacher->getCabinet(),
            $teacher->getEmail()
        );
        $class = $lesson->getClass();
        $classDto = new ClassDto(
            $class->getId(),
            $class->getNumber(),
            $class->getLetter()
        );
        return new LessonDto(
            $lesson->getId(),
            $itemDto,
            $lesson->getDate()->format('Y.m.d G:i'),
            $lesson->getLessonDuration(),
            $teacherDto,
            $classDto
        );
    }

    /**
     *  преобразует критерии поиска в массив
     *
     * @param SearchLessonServiceCriteria $searchCriteria - критерии поиска
     * @return array
     */
    private function searchCriteriaForArray(SearchLessonServiceCriteria $searchCriteria): array
    {
        $criteriaForRepository = [
            'id' => $searchCriteria->getId(),
            'item_name' => $searchCriteria->getItemName(),
            'item_description' => $searchCriteria->getItemDescription(),
            'lesson_date' => $searchCriteria->getDate(),
            'teacher_fio_surname' => $searchCriteria->getTeacherSurname(),
            'teacher_fio_name' => $searchCriteria->getTeacherName(),
            'teacher_fio_patronymic' => $searchCriteria->getTeacherPatronymic(),
            'teacher_cabinet' => $searchCriteria->getTeacherCabinet(),
            'class_number' => $searchCriteria->getClassNumber(),
            'class_letter' => $searchCriteria->getClassLetter()
        ];
        return array_filter($criteriaForRepository, static function ($v): bool {
            return null !== $v;
        });
    }
}
