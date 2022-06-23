<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Command;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Description'
                ]
            ])
            ->add('price',IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix'
                ]
            ])
            ->add('stock',IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Stock'
                ]
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
            ])

            ->add('isActif')
        
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                'query_builder' => function(CategoryRepository $cr){
                    return $cr->createQueryBuilder('c')
                    ->where('c.categoryParent IS NOT NULL');
                },
                'expanded' => true,
                'multiple' => true
            ])
            ->add('brand', EntityType::class, [
                'class'=> Brand::class, 
                'choice_label' => 'label',
                'expanded' => true,
            ])

        ;
    }
  

  



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
