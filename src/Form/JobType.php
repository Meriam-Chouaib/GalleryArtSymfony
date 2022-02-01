<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Category;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name')
            ->add('Description')
            
            
            //upload file
            ->add('image', FileType::class, array('data_class' => null), [
                'label' => 'upload image',
                'required' => true
            ])

            ->add('category',EntityType::class,["class"=>Category::class,
            "label"=>'Catégorie',
            "choice_label"=>"titre"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
