<?php

namespace App\Form;

use App\Entity\Vacancy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacancyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('beginAt', DateType::class, [
                'label' => 'Début des vacances',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('endAt', DateType::class, [
                'label' => 'Fin des vacances',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Vacancy::class]);
    }
}