<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use Doctrine\ORM\EntityManagerInterface;
use JoJoBizzareCoders\DigitalJournal\Service\NewReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\NewAssessmentReportDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\ResultRegisteringAssessmentReportDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;
use Symfony\Component\Validator\Constraints as Assert;

class CreateRegisterAssessmentReportController extends AbstractController

{

    /**
     * Валидация данных
     *
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * Менеджер сущностей
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * Сервис по добавдению новой оценки
     *
     * @var NewReportService
     */
    private NewReportService $reportService;

    /**
     * @param NewReportService $reportService - Сервис по добавдению новой оценки
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     */
    public function __construct(
        NewReportService $reportService,
        ValidatorInterface $validator,
        EntityManagerInterface $em
    ) {
        $this->reportService = $reportService;
        $this->validator = $validator;
        $this->em = $em;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(Request $serverRequest): Response
    {
        try {
            $this->em->beginTransaction();
            $requestData = json_decode($serverRequest->getContent(), true, 10, JSON_THROW_ON_ERROR);
            $validationResult = $this->validateData($requestData);
            if (0 === count($validationResult)) {
                $responseDto = $this->runService($requestData);
                $httpCode = 201;
                $jsonData = $this->buildJsonData($responseDto);
            } else {
                $httpCode = 400;
                $jsonData = [
                    'status' => 'fail',
                    'message' => implode('. ', $validationResult)
                ];
            }
            $this->em->flush();
            $this->em->commit();
        } catch (Throwable $e) {
            $this->em->rollBack();
            $httpCode = 500;
            $jsonData = [
                'status' => 'fail',
                'message' => $e->getMessage()
            ];
        }
        return $this->json($jsonData, $httpCode);
    }

    /**
     * Запуск сервиса
     *
     * @param array $requestData
     * @return ResultRegisteringAssessmentReportDto
     * @throws \JsonException
     */
    private function runService(array $requestData): ResultRegisteringAssessmentReportDto
    {
        $requestDto = new NewAssessmentReportDto(
            $requestData['lesson_id'],
            $requestData['student_id'],
            $requestData['mark']
        );
        return $this->reportService->registerAssessmentReport($requestDto);
    }

    /**
     * Создания массива формата Json
     *
     * @param ResultRegisteringAssessmentReportDto $responseDto
     * @return void
     */
    private function buildJsonData(ResultRegisteringAssessmentReportDto $responseDto): array
    {
        return [
            'id' => $responseDto->getId()
        ];
    }

    /**
     * Валидирует входные данные
     *
     * @param $requestData
     * @return array
     * @throws \Exception
     */
    private function validateData($requestData): array
    {
        $constraint = [
            new Assert\Type(['type' => 'array', 'message' => 'Данные о новой оценке не являються массивом']),
            new Assert\Collection([
                    'allowExtraFields' => false,
                    'allowMissingFields' => false,
                    'missingFieldsMessage' => 'Отсутствует обязательное поле: {{ field }}',
                    'extraFieldsMessage' => 'Есть лишние полня: {{ field }}',
                    'fields' => [
                        'lesson_id' => [
                            new Assert\Type(['type' => 'int', 'message' => 'Id занятия должно быть целым числом']),
                            new Assert\NotBlank([
                                'message' => 'Отсутсвует информация о занятии',
                                'normalizer' => 'trim'
                            ])
                        ],
                        'student_id' => [
                            new Assert\Type(['type' => 'int', 'message' => 'Id студента должно быть целым числом']),
                            new Assert\NotBlank([
                                'message' => 'Отсутсвует информация о студенте',
                                'normalizer' => 'trim'
                            ])
                        ],
                        'mark' => [
                            new Assert\Type(['type' => 'int', 'message' => 'Значение оценки должно быть целым числом']),
                            new Assert\NotBlank([
                                'message' => 'Отсутсвует информация о оценке',
                                'normalizer' => 'trim'
                            ]),
                            new Assert\Length([
                                'min' => 1,
                                'max' => 5,
                                'minMessage' => 'Значение оценки не должно быть меньше 0, быть равным 0 или быть больше 5',
                                'maxMessage' => 'Значение оценки не должно быть меньше 0, быть равным 0 или быть больше 5',
                            ])
                        ],

                    ]
                ]
            )
        ];

        $errors = $this->validator->validate($requestData, $constraint);

        return array_map((static function($v){return $v->getMessage();}), $errors->getIterator()->getArrayCopy());
    }
}
