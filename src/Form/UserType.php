<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $builder->getData();

        $builder
            ->add('email', EmailType::class, [
                'attr' => ['autocomplete' => 'off']
            ])
            ->add('firstname', TextType::class, [
                'attr' => ['autocomplete' => 'off']
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['autocomplete' => 'off']
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Manager' => 'ROLE_MANAGER',
                    'User' => 'ROLE_USER',
                ],
                'expanded' => true,
                'multiple' => true,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ]);
        if ($user->getId() === null) {
            $builder->add('password', PasswordType::class, [
                'label' => 'Password',
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
