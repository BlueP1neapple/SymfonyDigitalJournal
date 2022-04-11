<?php

namespace JoJoBizzareCoders\DigitalJournal\Form;

use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\LessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\SearchLessonServiceCriteria;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\StudentDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchStudentService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type as FormElement;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Реализация настройки формы добаления нового акта оценки
 */
class CreateAssessmentReportForm extends AbstractType
{
    /**
     * Сервис поиска занятий
     *
     * @var SearchLessonService
     */
    private SearchLessonService $lessonService;

    /**
     * Сервис поиска студентов
     *
     * @var SearchStudentService
     */
    private SearchStudentService $searchStudentService;

    /**
     * @param SearchLessonService $lessonService - Сервис поиска занятий
     * @param SearchStudentService $searchStudentService - Сервис поиска студентов
     */
    public function __construct(
        SearchLessonService $lessonService,
        SearchStudentService $searchStudentService
    ) {
        $this->lessonService = $lessonService;
        $this->searchStudentService = $searchStudentService;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('lesson_id', FormElement\ChoiceType::class,
            [
                'required' => true,
                'multiple' => false,
                'label' => 'Занятие',
                'choices' => $this->lessonService->search(new SearchLessonServiceCriteria()),
                'choice_label' => static function (LessonDto $lessonDto): string {
                    return $lessonDto->getItem()->getName() . ' ' . $lessonDto->getTeacher()->getFio()['surname'] .
                        ' ' . $lessonDto->getClass()->getNumber() . ' ' . $lessonDto->getClass()->getLetter();
                },
                'choice_value' => static function ($lessonDto): string {
                    return is_null($lessonDto) ?  : $lessonDto->getId();
                }
            ]
        )
            ->add('student_id', FormElement\ChoiceType::class,
                [
                    'required' => true,
                    'multiple' => false,
                    'label' => 'Студент',
                    'choices' => $this->searchStudentService->search(),
                    'choice_label' => static function (StudentDto $studentDto): string {
                        $fio = $studentDto->getFio();
                        return $fio['surname'] . ' ' . $fio['name'] . ' ' . $fio['patronymic'] .
                            ' ' . $studentDto->getClass()->getNumber() . ' ' . $studentDto->getClass()->getLetter();
                    },
                    'choice_value' => static function ($studentDto): string {
                        return is_null($studentDto) ?  : $studentDto->getId();
                    }
                ]
            )
            ->add('mark', FormElement\TextType::class,
                [
                    'required' => true,
                    'label' => 'Значение оценки',
                    'constraints' => [
                        new Assert\Type(
                            [
                                'type' => 'string',
                                'message' => 'Данные о значении оценки должны быть строкой'
                            ]
                        ),
                        new Assert\NotBlank(
                            [
                                'normalizer' => 'trim',
                                'message' => 'Данные о значении оценок не должны быть пустой строкой'
                            ]
                        ),
                        new Assert\Range(
                            [
                                'min' => 1,
                                'max' => 5,
                                'minMessage' => 'Значение оценки должно быть 1 или больше.',
                                'maxMessage' => 'Значение оценки должно быть 5 или меньше.'
                            ]
                        )
                    ]
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
