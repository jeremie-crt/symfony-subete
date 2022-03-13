<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tags;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('intro', TextType::class)
            ->add('content', TextareaType::class)

            ->add('image', TextType::class)

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'multiple' => true,
            ])

            ->add('extra_categories', CollectionType::class, [
                'entry_type' => CategoryType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false
            ])

            ->add('tags', EntityType::class, [
                'class' => Tags::class,
                'choice_label' => 'title',
                'multiple' => true,
            ])

            ->add('extra_tags', CollectionType::class, [
                'entry_type' => TagsType::class,
                'entry_options' => [ 'label' => false ],
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
