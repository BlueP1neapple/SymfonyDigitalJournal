<?php

namespace JoJoBizzareCoders\DigitalJournal\Service;

use JoJoBizzareCoders\DigitalJournal\Entity\TeacherRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use Psr\Log\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Service\SearchItemService\ItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchTeacherService\TeacherDto;

class SearchTeacherService
{
    /**
     * Используемый логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Репозиторий учетелй
     *
     * @var TeacherRepositoryInterface
     */
    private TeacherRepositoryInterface $teacherRepository;

    /**
     * @param LoggerInterface $logger
     * @param TeacherRepositoryInterface $teacherRepository
     */
    public function __construct(
        LoggerInterface $logger,
        TeacherRepositoryInterface $teacherRepository
    ) {
        $this->logger = $logger;
        $this->teacherRepository = $teacherRepository;
    }

    public function search(): array
    {
        $entitiesCollection = $this->teacherRepository->findBy([]);
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            if ($entity instanceof TeacherUserClass) {
                $dtoCollection[] = $this->createDto($entity);
            }
        }
        $this->logger->info('found item: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    private function createDto(TeacherUserClass $entity): TeacherDto
    {
        return new TeacherDto(
            $entity->getId(),
            [
                'surname'    => $entity->getFio()->getSurname(),
                'name'       => $entity->getFio()->getName(),
                'patronymic' => $entity->getFio()->getPatronymic()
            ],
            $entity->getDateOfBirth()->format('Y-m-d'),
            $entity->getPhone(),
            [
                'street'    => $entity->getAddress()->getStreet(),
                'home'      => $entity->getAddress()->getHome(),
                'apartment' => $entity->getAddress()->getApartment()
            ],
            new ItemDto(
                $entity->getItem()->getId(),
                $entity->getItem()->getName(),
                $entity->getItem()->getDescription()
            ),
            $entity->getCabinet(),
            $entity->getEmail()
        );
    }
}
