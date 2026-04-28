<?php

namespace App\Form;

use App\Entity\Booklet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('weekNumber', IntegerType::class, [
                'label' => 'Numéro de semaine',
                'attr' => ['class' => 'form-control']
            ])
            ->add('weekStart', DateType::class, [
                'label' => 'Semaine du',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('weekContent', TextareaType::class, [
                'label' => 'Activités de la semaine',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 8,
                    'placeholder' => 'Décrivez les activités réalisées cette semaine...'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Booklet::class]);
    }
}