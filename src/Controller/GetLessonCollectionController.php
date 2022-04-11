<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use Psr\Log\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\LessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\SearchLessonServiceCriteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Контроллер отвечающий за поиск занятий
 */
class GetLessonCollectionController extends AbstractController
{

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * Используемый логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Сервис поиска занятий
     *
     * @var SearchLessonService
     */
    private SearchLessonService $searchLessonService;

    /**
     * Конструктор контроллера поиска занятий
     *
     * @param LoggerInterface $logger - Используемый логгер
     * @param SearchLessonService $searchLessonService
     * @param ValidatorInterface $validator
     */
    public function __construct(
        LoggerInterface $logger,
        SearchLessonService $searchLessonService,
        ValidatorInterface $validator
    ) {
        $this->logger = $logger;
        $this->searchLessonService = $searchLessonService;
        $this->validator = $validator;
    }

    /**
     * Валидирует параметры запроса
     *
     * @param Request $serverRequest - объект сервернного запроса http
     * @return string|null - строка с ошибкой или null если ошибки нет
     */
    private function validateQueryParams(Request $serverRequest): ?string
    {
        $pram = array_merge($serverRequest->query->all(), $serverRequest->attributes->all());
        $constraint = new Assert\Collection(
            [
                'allowExtraFields' => true,
                'allowMissingFields' => true,
                'fields' => [
                    'item_name' => new Assert\Optional(
                        [
                            new Assert\Type(['type' => 'string', 'message' => 'Incorrect item name'])
                        ]
                    ),
                    'item_description' => new Assert\Optional(
                        [
                            new Assert\Type(['type' => 'string', 'message' => 'Incorrect item description'])
                        ]
                    ),
                    'lesson_date' => new Assert\Optional(
                        [
                            new Assert\Type(['type' => 'string', 'message' => 'incorrect lesson_date'])
                        ]
                    ),
                    'teacher_fio' => new Assert\Optional(
                        [
                            new Assert\Type(['type' => 'string', 'message' => 'incorrect teacher_fio'])
                        ]
                    ),
                    'teacher_cabinet' => new Assert\Optional(
                        [
                            new Assert\Type(['type' => 'string', 'message' => 'incorrect teacher_cabinet'])
                        ]
                    ),
                    'class_number' => new Assert\Optional(
                        [
                            new Assert\Type(['type' => 'string', 'message' => 'incorrect class_number'])
                        ]
                    ),
                    'class_letter' => new Assert\Optional(
                        [
                            new Assert\Type(['type' => 'string', 'message' => 'incorrect class_letter'])
                        ]
                    )
                ],
            ]
        );
        $errors = $this->validator->validate($pram, $constraint);
        $errStrCollection = array_map(
            (static function ($v) {
                return $v->getMessage();
            }),
            $errors->getIterator()->getArrayCopy()
        );
        return count($errStrCollection) > 0 ? implode(',', $errStrCollection) : null;
    }

    /**
     * Обработка запроса поиска занятия
     *
     * @param Request $serverRequest - объект серверного http запроса
     * @return Response - объект http ответа
     */
    public function __invoke(Request $serverRequest): Response
    {
        $this->logger->info('dispatch "lesson" url');
        $resultOfParamsValidation = $this->validateQueryParams($serverRequest);
        if (null === $resultOfParamsValidation) {
            $params = array_merge($serverRequest->query->all(), $serverRequest->attributes->all());
            $searchLessonServiceCriteria = new SearchLessonServiceCriteria();
            $searchLessonServiceCriteria->setItemName($params['item_name'] ?? null);
            $searchLessonServiceCriteria->setItemDescription($params['item_description'] ?? null);
            $searchLessonServiceCriteria->setDate($params['lesson_date'] ?? null);
            $searchLessonServiceCriteria->setTeacherCabinet($params['teacher_cabinet'] ?? null);
            $searchLessonServiceCriteria->setClassNumber($params['class_number'] ?? null);
            $searchLessonServiceCriteria->setClassLetter($params['class_letter'] ?? null);
            $searchLessonServiceCriteria->setId($params['id'] ?? null);
            $searchLessonServiceCriteria->setTeacherSurname($params['teacher_fio_surname'] ?? null);
            $searchLessonServiceCriteria->setTeacherName($params['teacher_fio_name'] ?? null);
            $searchLessonServiceCriteria->setTeacherPatronymic($params['teacher_fio_patronymic'] ?? null);
            $foundLessons = $this->searchLessonService->search($searchLessonServiceCriteria);
            $httpCode = $this->buildHttpCode($foundLessons);
            $result = $this->buildResult($foundLessons);
        } else {
            $httpCode = 500;
            $result = [
                'status' => 'fail',
                'message' => $resultOfParamsValidation
            ];
        }
        return $this->json($result, $httpCode);
    }


    /**
     * Создаёт http код
     *
     * @param array $foundLesson - коллекция найденных занятий
     * @return int
     */
    protected function buildHttpCode(array $foundLesson): int
    {
        return 200;
    }

    /**
     * Создаёт результат поиска занятий
     *
     * @param LessonDto[] $foundLessons - коллекция найденных занятий
     * @return array
     */
    protected function buildResult(array $foundLessons)
    {
        $result = [];
        foreach ($foundLessons as $foundLesson) {
            $result[] = $this->serializeLesson($foundLesson);
        }
        return $result;
    }

    /**
     * Серилизация dto объекта с информацией об занятиях
     *
     * @param LessonDto $foundLesson - dto объект с информацией об занятиях
     * @return array
     */
    final protected function serializeLesson(LessonDto $foundLesson): array
    {
        $jsonData = [
            'id' => $foundLesson->getId(),
            'date' => $foundLesson->getDate(),
            'lessonDuration' => $foundLesson->getLessonDuration(),
        ];
        $itemDto = $foundLesson->getItem();
        $jsonData['item'] = [
            'id' => $itemDto->getId(),
            'name' => $itemDto->getName(),
            'description' => $itemDto->getDescription()
        ];
        $teacherDto = $foundLesson->getTeacher();
        $teacherFioDto = $teacherDto->getFio();
        $teacherAddressDto = $teacherDto->getAddress();
        $jsonData['teacher'] = [
            'id' => $teacherDto->getId(),
            'fio' => [
                'surname' => $teacherFioDto['surname'],
                'name' => $teacherFioDto['name'],
                'patronymic' => $teacherFioDto['patronymic']
            ],
            'dateOfBirth' => $teacherDto->getDateOfBirth(),
            'phone' => $teacherDto->getPhone(),
            'address' => [
                'street' => $teacherAddressDto->getStreet(),
                'home' => $teacherAddressDto->getHome(),
                'apartment' => $teacherAddressDto->getApartment()
            ],
            'item' => $jsonData['item'],
            'cabinet' => $teacherDto->getCabinet(),
            'email' => $teacherDto->getEmail()
        ];
        $classDto = $foundLesson->getClass();
        $jsonData['class'] = [
            'id' => $classDto->getId(),
            'number' => $classDto->getNumber(),
            'letter' => $classDto->getLetter()
        ];
        return $jsonData;
    }
}
