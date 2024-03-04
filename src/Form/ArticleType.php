<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityRepository;
use App\Repository\CategorieRepository;
use Doctrine\DBAL\Query\QueryBuilder as QueryQueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
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
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image:',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\'image',
                'image_uri' => true,
                'download_uri' => false,
            ])


            ->add('description', TextareaType::class, [
                'label' => 'Description:',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ma description',
                    'rows' => '10'
                ]
            ])

            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'label' => 'Catégorie:',
                'placeholder' => 'Catégorie',
                'choice_label' => 'titre',
                'required' => false,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.enable = :enable')
                        ->setParameter('enable', true)
                        ->orderBy('c.titre', 'ASC');
                },
                'expanded' => false,
                'multiple' => true,
                // Relation ManyToMany donc by reference false pour persister en BDD
                'by_reference' => false,
                'autocomplete' => true,

            ])


            ->add('enable', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'sanitize_html' => true,
        ]);
    }
}
