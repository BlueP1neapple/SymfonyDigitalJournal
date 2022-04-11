<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\ParentDto;
use Psr\Log\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Service\SearchAssessmentReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\AssessmentReportDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\SearchReportAssessmentCriteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Контроллер отвечающий за поиск оценок
 */
class GetAssessmentReportCollectionController extends AbstractController
{


    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * Использвуемый логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Сервис поиска оценок
     *
     * @var SearchAssessmentReportService
     */
    private SearchAssessmentReportService $assessmentReportService;


    /**
     * Конструктор поиска Оценок
     *
     * @param LoggerInterface $logger - используемый логгер
     * @param SearchAssessmentReportService $SearchAssessmentReportService - сервис поиска оценок
     * @param ValidatorInterface $validator
     */
    public function __construct(
        LoggerInterface $logger,
        SearchAssessmentReportService $SearchAssessmentReportService,
        ValidatorInterface $validator
    ) {
        $this->logger = $logger;
        $this->assessmentReportService = $SearchAssessmentReportService;
        $this->validator = $validator;
    }

    /**
     * Валидирует парметры запроса
     *
     * @param Request $serverRequest - серверный http запрос
     * @return string|null - возвращает сообщение об ошибке, или null если ошибки нет.
     */
    private function ValidateQueryParams(Request $serverRequest): ?string
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
                            new Assert\Type(['type' => 'string', 'message' => 'Incorrect lesson date'])
                        ]
                    ),
                    'student_fio' => new Assert\Optional(
                        [
                            new Assert\Type(['type' => 'string', 'message' => 'Incorrect student fio'])
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
     * Обработка запроса поиска оценок
     *
     * @param Request $serverRequest - http запрос
     * @return Response - http ответ
     */
    public function __invoke(Request $serverRequest): Response
    {
        $this->logger->info('assessmentReport" url');
        $resultOfParamValidation = $this->ValidateQueryParams($serverRequest);
        if (null === $resultOfParamValidation) {
            $params = array_merge($serverRequest->query->all(), $serverRequest->attributes->all());
            $searchAssessmentReportCriteria = new SearchReportAssessmentCriteria();
            $searchAssessmentReportCriteria->setId($params['id'] ?? null);
            $searchAssessmentReportCriteria->setItemName($params['item_name'] ?? null);
            $searchAssessmentReportCriteria->setItemDescription($params['item_description'] ?? null);
            $searchAssessmentReportCriteria->setLessonDate($params['lesson_date'] ?? null);
            $searchAssessmentReportCriteria->setStudentSurname($params['student_fio_surname'] ?? null);
            $searchAssessmentReportCriteria->setStudentName($params['student_fio_name'] ?? null);
            $searchAssessmentReportCriteria->setStudentPatronymic($params['student_fio_patronymic'] ?? null);
            $foundReport = $this->assessmentReportService->search($searchAssessmentReportCriteria);
            $httpCode = $this->buildHttpCode($foundReport);
            $result = $this->buildResult($foundReport);
        } else {
            $httpCode = 500;
            $result = [
                'status' => 'fail',
                'message' => $resultOfParamValidation
            ];
        }
        return $this->json($result ,$httpCode);
    }

    /**
     * Создаёт http код
     *
     * @param array $foundReport - коллекция найденных оценок
     * @return int
     */
    protected function buildHttpCode(array $foundReport): int
    {
        return 200;
    }

    /**
     * Создаёт результат поиска оценок
     *
     * @param AssessmentReportDto[] $foundReports - коллекция найденных оценок
     * @return array
     */
    protected function buildResult(array $foundReports)
    {
        $result = [];
        foreach ($foundReports as $foundReport) {
            $result[] = $this->serializeReport($foundReport);
        }
        return $result;
    }

    /**
     * Логика формирования jsonData
     *
     * @param AssessmentReportDto $reportDto - объект dto c информацией об оценках
     * @return array
     */
    protected function serializeReport(AssessmentReportDto $reportDto): array
    {
        return [
            'id' => $reportDto->getId(),
            'lesson' => [
                'id' => $reportDto->getLesson()->getId(),
                'item' => [
                    'id' => $reportDto->getLesson()->getItem()->getId(),
                    'name' => $reportDto->getLesson()->getItem()->getName(),
                    'description' => $reportDto->getLesson()->getItem()->getDescription()
                ],
                'date' => $reportDto->getLesson()->getDate(),
                'lessonDuration' => $reportDto->getLesson()->getLessonDuration(),
                'teacher' => [
                    'id' => $reportDto->getLesson()->getTeacher()->getId(),
                    'fio' => [
                        'surname' => $reportDto->getLesson()->getTeacher()->getFio()['surname'],
                        'name' => $reportDto->getLesson()->getTeacher()->getFio()['name'],
                        'patronymic' => $reportDto->getLesson()->getTeacher()->getFio()['patronymic'],
                    ],
                    'dateOfBirth' => $reportDto->getLesson()->getTeacher()->getDateOfBirth(),
                    'phone' => $reportDto->getLesson()->getTeacher()->getPhone(),
                    'address' => [
                        'street'    => $reportDto->getLesson()->getTeacher()->getAddress()['street'],
                        'home'      => $reportDto->getLesson()->getTeacher()->getAddress()['home'],
                        'apartment' => $reportDto->getLesson()->getTeacher()->getAddress()['apartment'],
                    ],
                    'item' => [
                        'id' => $reportDto->getLesson()->getItem()->getId(),
                        'name' => $reportDto->getLesson()->getItem()->getName(),
                        'description' => $reportDto->getLesson()->getItem()->getDescription()
                    ],
                    'cabinet' => $reportDto->getLesson()->getTeacher()->getCabinet(),
                    'email' => $reportDto->getLesson()->getTeacher()->getEmail()
                ],
                'class' => [
                    'id' => $reportDto->getLesson()->getClass()->getId(),
                    'number' => $reportDto->getLesson()->getClass()->getNumber(),
                    'letter' => $reportDto->getLesson()->getClass()->getLetter()
                ]
            ],
            'student' => [
                'id' => $reportDto->getStudent()->getId(),
                'fio' => [
                    'surname' => $reportDto->getStudent()->getFio()['surname'],
                    'name' => $reportDto->getStudent()->getFio()['name'],
                    'patronymic' => $reportDto->getStudent()->getFio()['patronymic']
                ],
                'dateOfBirth' => $reportDto->getStudent()->getDateOfBirth(),
                'phone' => $reportDto->getStudent()->getPhone(),
                'address' => [
                    'street' => $reportDto->getStudent()->getAddress()['street'],
                    'home' => $reportDto->getStudent()->getAddress()['home'],
                    'apartment' => $reportDto->getStudent()->getAddress()['apartment']
                ],
                'class' => [
                    'id' => $reportDto->getStudent()->getClass()->getId(),
                    'number' => $reportDto->getStudent()->getClass()->getNumber(),
                    'letter' => $reportDto->getStudent()->getClass()->getLetter()
                ],
                'parents' => $this->loadParents($reportDto->getStudent()->getParents()),
            ],
            'mark' => $reportDto->getMark()
        ];
    }

    private function loadParents(array $parentsList): array
    {
        if (0 === count($parentsList)) {
            return [];
        }

        $jsonData = array_values(array_map(static function (ParentDto $dto) {
            return[
                'id' => $dto->getId(),
                'email' => $dto->getEmail(),
                'placeOfWork' => $dto->getPlaceOfWork(),
                'phone' => $dto->getPhone(),
                'dateOfBirth' => $dto->getDateOfBirth(),
                'fio' => [
                    'surname' => $dto->getFio()['surname'],
                    'name' => $dto->getFio()['name'],
                    'patronymic' => $dto->getFio()['patronymic']
                ],
                'address' => [
                    'street' => $dto->getAddress()['street'],
                    'home' => $dto->getAddress()['home'],
                    'apartment' => $dto->getAddress()['apartment']
                ]
            ];
        }, $parentsList));
        return $jsonData;
    }
}