<?php

namespace JoJoBizzareCoders\DigitalJournal\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type as FormElement;
class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('POST')
            ->add(
                'login',
                FormElement\TextType::class,
                [
                    'required' => true,
                    'label' => 'Логин',
                    'constraints' => [
                        new Assert\Type(['type' => 'string', 'message' => 'Логин должен быть строкой']),
                    ]
                ]
            )
            ->add(
                'password',
                FormElement\PasswordType::class,
                [
                    'required' => true,
                    'label' => 'Пароль',
                    'constraints' => [
                        new Assert\Type(['type' => 'string', 'message' => 'Пароль имеет не верный формат']),
                    ]
                ]
            )
            ->add('submit',
                FormElement\SubmitType::class,
                ['label' => 'Войти']
            );
        parent::buildForm($builder, $options);
    }


    public function getBlockPrefix(): string
    {
        return '';
    }
}