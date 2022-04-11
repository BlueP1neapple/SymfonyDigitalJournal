<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use Doctrine\ORM\EntityManagerInterface;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService\NewItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService\ResultRegisteringItemDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;
use Symfony\Component\Validator\Constraints as Assert;

class CreateRegisterItemController extends AbstractController
{
    /**
     * Менеджер сущностей
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * Сервис создания Предметов
     *
     * @var NewItemService
     */
    private NewItemService $newItemService;

    /**
     * Валидация данных
     *
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;


    /**
     * @param NewItemService $newItemService
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     */
    public function __construct(
        NewItemService $newItemService,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ) {
        $this->newItemService = $newItemService;
        $this->em = $em;
        $this->validator = $validator;
    }

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
            $this->em->commit();
            $this->em->flush();
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
     * @param array $data
     * @return ResultRegisteringItemDto
     */
    private function runService(array $data): ResultRegisteringItemDto
    {
        $requestDto = new NewItemDto(
            $data['name'],
            $data['description']
        );
        return $this->newItemService->registerItem($requestDto);
    }

    /**
     * Создания массива формата Json
     *
     * @param ResultRegisteringItemDto $responseDto
     * @return void
     */
    private function buildJsonData(ResultRegisteringItemDto $responseDto): array
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
     */
    private function validateData($requestData): array
    {
        $constraint = [
            new Assert\Type(['type' => 'array', 'message' => 'Данные о новой предмете не массив']),
            new Assert\Collection([
                    'allowExtraFields' => false,
                    'allowMissingFields' => false,
                    'missingFieldsMessage' => 'Отсутствует обязательное поле: {{ field }}',
                    'extraFieldsMessage' => 'Есть лишние полня: {{ field }}',
                    'fields' => [
                        'name' => [
                            new Assert\Type(['type' => 'string', 'message' => 'Название предмета должено быть строкой']),
                            new Assert\NotBlank([
                                'message' => 'Отсутсвует название предмета',
                                'normalizer' => 'trim'
                            ])
                        ],
                        'description' => [
                            new Assert\Type(['type' => 'string', 'message' => 'description предмета должен быть строкой']),
                            new Assert\NotBlank([
                                'message' => 'Отсутсвует description предмета',
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
}
