<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre:',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Mon titre',
                ]
            ])

            ->add('note', RangeType::class, [
                'label' => 'Note',
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 1,
                ],
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Description:',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ma description',
                    'rows' => '10'
                ]
            ])

            ->add('rgpd', CheckboxType::class, [
                'label' => 'RGPD',
                'required' => true,
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
            'sanitize_html' => true,
        ]);
    }
}
