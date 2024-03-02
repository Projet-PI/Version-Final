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


class UserModifyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', null, [
                'label' => 'Nom ',
                'empty_data' => ''
            ])
            ->add('Prenom', null, [
                'label' => 'Prenom ',
                'empty_data' => ''
            ])
            ->add('Adresse', null, [
                'label' => 'Adresse ',
                'empty_data' => ''
            ])
            ->add('Email', null, [
                'label' => 'Email ',
                'empty_data' => ''
            ])
            ->add('NumTel', null, [
                'label' => 'NumTel ',
                'empty_data' => ''
            ])
            ->add('Profession', null, [
                'label' => 'Profession ',
                'empty_data' => ''
            ])

            ->add('CIN', null, [
                'label' => 'CIN ',
                'empty_data' => ''
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
