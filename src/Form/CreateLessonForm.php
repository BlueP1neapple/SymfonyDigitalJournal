<?php

namespace JoJoBizzareCoders\DigitalJournal\Form;

use JoJoBizzareCoders\DigitalJournal\Service\SearchClassService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchItemService\SearchItemServiceCriteria;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\ClassDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchItemService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchItemService\ItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchTeacherService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchTeacherService\TeacherDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type as FormElement;


/**
 * Реализация настройки формы добавления нового занятия
 */
class CreateLessonForm extends AbstractType
{
    /**
     * Сервис поиска предметов
     *
     * @var SearchItemService
     */
    private SearchItemService $itemService;

    /**
     * Сервис поиска учителей
     *
     * @var SearchTeacherService
     */
    private SearchTeacherService $teacherService;

    /**
     * Сервис поиска классов
     *
     * @var SearchClassService
     */
    private SearchClassService $classService;

    /**
     * @param SearchItemService $itemService - Сервис поиска предметов
     * @param SearchTeacherService $teacherService - Сервис поиска учителей
     * @param SearchClassService $classService - Сервис поиска классов
     */
    public function __construct(
        SearchItemService $itemService,
        SearchTeacherService $teacherService,
        SearchClassService $classService
    ) {
        $this->itemService = $itemService;
        $this->teacherService = $teacherService;
        $this->classService = $classService;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('item_id', FormElement\ChoiceType::class,
            [
                'required' => true,
                'label' => 'Предмет',
                'multiple' => false,
                'choices' => $this->itemService->search(new SearchItemServiceCriteria()),
                'choice_label' => static function (ItemDto $itemDto): string {
                    return $itemDto->getName() . ' ' . $itemDto->getDescription();
                },
                'choice_value' => static function ( $itemDto ): string {
                    return is_null($itemDto) ?  : $itemDto->getId();
                }
            ]
        )
            ->add('date', FormElement\DateTimeType::class,
                [
                    'required' => true,
                    'label' => 'Дата проведения занятия',
                    'input'  => 'datetime_immutable',
                    'constraints' => [

                    ]
                ]
            )
            ->add('lesson_duration', FormElement\TextType::class,
                [
                    'data' => '40',
                    'required' => true,
                    'label' => 'Продолжительность занятия',
                    'constraints' => [
                        new Assert\Type(
                            [
                                'type' => 'string',
                                'message' => 'Данные о времени продолжения занятия должны быть строкой'
                            ]
                        ),
                        new Assert\Positive(
                            [
                                'message' => 'время проведения должен быть больше 0'
                            ]
                        )
                    ]
                ]
            )
            ->add('teacher_id', FormElement\ChoiceType::class,
                [
                    'required' => true,
                    'multiple' => false,
                    'label' => 'Учитель',
                    'choices' => $this->teacherService->search(),
                    'choice_label' => static function (TeacherDto $teacherDto): string {
                        return $teacherDto->getFio()['surname'] . ' ' . $teacherDto->getFio()['name'] . ' '
                            . $teacherDto->getFio()['patronymic'];
                    },
                    'choice_value' => static function ( $teacherDto): string {
                        return is_null($teacherDto) ?  : $teacherDto->getId();
                    }
                ]
            )
            ->add('class_id', FormElement\ChoiceType::class,
                [
                    'required' => true,
                    'multiple' => false,
                    'label' => 'Класс',
                    'choices' => $this->classService->search(),
                    'choice_label' => static function (ClassDto $classDto): string {
                        return $classDto->getNumber() . ' ' . $classDto->getLetter();
                    },
                    'choice_value' => static function ($classDto): string {
                        return is_null($classDto) ?  : $classDto->getId();
                    }
                ]
            )
            ->add('submit', FormElement\SubmitType::class,
                [
                    'label' => 'Добавить'
                ]
            )
            ->setMethod('POST');
        parent::buildForm($builder, $options);
    }
}
