<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use Doctrine\ORM\EntityManagerInterface;
use JoJoBizzareCoders\DigitalJournal\Service\NewLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\NewLessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\ResultRegistrationLessonDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;
use Symfony\Component\Validator\Constraints as Assert;

class CreateRegisterLessonController extends AbstractController
{

    /**
     * Менеджер сущностей
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * Валидация данных
     *
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * Сервис для создания урока
     *
     * @var NewLessonService
     */
    private NewLessonService $newLessonService;

    /**
     * @param NewLessonService $newLessonService
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     */
    public function __construct(
        NewLessonService $newLessonService,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ) {
        $this->newLessonService = $newLessonService;
        $this->em = $em;
        $this->validator = $validator;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(Request $serverRequest): Response
    {
        try {
            $this->em->beginTransaction();
            $requestData = json_decode($serverRequest->getContent(), true, 20, JSON_THROW_ON_ERROR);
            $validationResult = $this->validationData($requestData);

            if (0 === count($validationResult)) {
                $responseDto = $this->runService($requestData);
                $httpCode = 201;
                $jsonData = $this->buildJsondata($responseDto);
            } else {
                $httpCode = 400;
                $jsonData = ['status' => 'fail', 'massage' => implode('. ', $validationResult)];
            }
            $this->em->commit();
            $this->em->flush();
        } catch (Throwable $e) {
            $this->em->rollBack();
            $httpCode = 500;
            $jsonData = ['status' => 'fail', 'massage' => $e->getMessage()];
        }

        return $this->json($jsonData, $httpCode);
    }

    private function validationData($requestData)
    {
        $constraint = [
            new Assert\Type(['type' => 'array', 'message' => 'Данные о новом уроке не являются массивом']),
            new Assert\Collection([
                    'allowExtraFields' => false,
                    'allowMissingFields' => false,
                    'missingFieldsMessage' => 'Отсутствует обязательное поле: {{ field }}',
                    'extraFieldsMessage' => 'Есть лишние полня: {{ field }}',
                    'fields' => [
                        'item_id' => [
                            new Assert\Type(['type' => 'int', 'message' => 'айди предмета должен быть числом']),
                        ],
                        'date' => [
                            new Assert\Type(['type' => 'string', 'message' => 'дата урока должна быть строкой']),
                            new Assert\NotBlank([
                                'message' => 'отсутствует дата урока',
                                'normalizer' => 'trim'
                            ])
                        ],
                        'teacher_id' => [
                            new Assert\Type(['type' => 'int', 'message' => 'id преподавателя должно быть предстваленна целым числом']),
                            new Assert\NotBlank([
                                'message' => 'отсутствует преподаватель',
                                'normalizer' => 'trim'
                            ])
                        ],
                        'class_id' => [
                            new Assert\Type(['type' => 'int', 'message' => 'id класса должно быть предстваленна целым числом']),
                            new Assert\NotBlank([
                                'message' => 'отсутствует класс',
                                'normalizer' => 'trim'
                            ])
                        ],
                    ]
                ]
            )
        ];

        $errors = $this->validator->validate($requestData, $constraint);

        return array_map((static function($v){return $v->getMessage();}), $errors->getIterator()->getArrayCopy());
    }

    /**
     *
     * @param array $requestData
     * @return ResultRegistrationLessonDto
     */
    private function runService(array $requestData): ResultRegistrationLessonDto
    {
        $requestDto = new NewLessonDto(
            $requestData['item_id'],
            $requestData['date'],
            $requestData['lessonDuration'],
            $requestData['teacher_id'],
            $requestData['class_id'],
        );
        return $this->newLessonService->registerLesson($requestDto);
    }

    private function buildJsondata(ResultRegistrationLessonDto $responseDto)
    {
        return[
            'id' => $responseDto->getId()

        ];
    }
}
