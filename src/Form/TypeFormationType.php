<?php

namespace App\Form;

use App\Entity\TypeFormation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Intitulé du titre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('code', TextType::class, [
                'label' => 'Code RNCP',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('detail', TextType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => TypeFormation::class]);
    }
}