<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EditUSerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //accés d'administrateur lel user (par example ybadel role mte3 user)
        $builder
            ->add('roles',ChoiceType::Class,[
                'choices'=> [
                'Utilisateur' => 'ROLE_USER',
                'Editeur' => 'ROLE_EDITOR',
                'Administrator' => 'ROLE_ADMIN'
            ],
            'expanded' => true, 
            'multiple' => true,
            'label' => 'Roles'
        ]);
      
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
