<?php

namespace JoJoBizzareCoders\DigitalJournal\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormElement;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Настраиваемые формы
 */
class CreateItemForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            FormElement\TextType::class,
            [
                'required' => true,
                'label' => 'Название предмета',
                'priority' => 300,
                'constraints' => [
                    new Assert\Type(['type' => 'string',
                        'message' => 'Данные о предмете должны быть строкой']),
                    new Assert\NotBlank([
                        'message' => 'Данные о предмете не могут быть пустыми',
                        'normalizer' => 'trim'
                    ]),
                    new Assert\Length([
                        'min' => 1,
                        'max' => 255,
                        'minMessage' => 'Не корректная длинна названия минимум {{ limit }} символов',
                        'maxMessage' => 'Не корректная длинна названия',
                    ])
                ]
            ]
        )
            ->add(
                'description',
                FormElement\TextType::class,
                [
                    'required' => true,
                    'label' => 'Расшифровка предмета',
                    'priority' => 200,
                    'constraints' => [
                        new Assert\Type(['type' => 'string', 'message' => 'Расшифровка должна быть строкой']),
                    ]
                ]
            )
            ->add(
                'submit',
                FormElement\SubmitType::class,
                [
                    'label' => 'Добавить',
                    'priority' => -1
                ]
            )->setMethod('POST');

        parent::buildForm($builder, $options);
    }

}