<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Furniture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FurnitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('image', FileType::class, ['mapped' => false])
            ->add('type')
            ->add('category', EntityType::class, [
                'class' => Category::class
            ])
            ->add('price')
            ->add('quantity')
            ->add('description')
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Furniture::class,
        ]);
    }
}
