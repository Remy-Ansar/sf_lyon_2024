<?php

namespace App\Form;

use Assert\Regex;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Prénom:',
                'required' => false,
                'attr' => [
                    'placeholder' => 'John',
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom:',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Doe',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email:',
                'required' => false,
                'attr' => [
                    'placeholder' => 'john.doe@email.com:',
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'Mot de passe',
                'required' => true,
                'mapped' => false,
                'invalid_message' => "Les mots de passe ne correspondent pas",
                'first_options' => [
                    'label' => "Mot de passe:",
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Length([
                            'max' => 4096
                        ]),
                        new Assert\Regex(
                            pattern: '/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,16}$/',
                            message: "La force du mot de passe est trop faible. Veuillez respecter les conditions."
                        ),
                    ],
                    'help' => "Le mot de passe doit contenir au minimum 1 lettre majuscule, minuscule, 1 chiffre et charactère spécial"
                ],
                'second_options' => [
                    'label' => "Confirmation de mot passe:"
                ]
            ]);

        if ($options['isAdmin']) {
            $builder->remove('password')
                ->add('roles', ChoiceType::class, [
                    'label' => 'Roles:',
                    'placeholder' => 'Sélectionner un rôle',
                    'choices' => [
                        'Administrateur' => 'ROLE_ADMIN',
                    ],
                    'expanded' => true,
                    'multiple' => true,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'isAdmin' => false,
        ]);
    }
}
