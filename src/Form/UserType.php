<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', null, [
                'constraints' => [

                ],
            ])
            ->add('Prenom', null, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('Adresse', null,[
        'constraints' => [
            new NotBlank(),
                 ],
             ])
            ->add('Email', EmailType::class, [
                'constraints' => [
                    new Email(),
                ],
            ])
            ->add('NumTel', null, [
                'constraints' => [
                    new Type('numeric'),
                    new Length(['min' => 8]),
                ],
            ])
            ->add('Profession', null,[
            'constraints' => [
                    new NotBlank(),
                 ],
            ])
            ->add('Password', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('CIN', null, [
                'constraints' => [
                    new Type('numeric'),
                    new Length(['min' => 8]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
