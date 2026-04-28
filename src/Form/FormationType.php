<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\TypeFormation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la formation',
                'attr' => ['class' => 'form-control']
            ])
            ->add('beginAt', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('endAt', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('beginStageAt', DateType::class, [
                'label' => 'Début stage',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('endStageAt', DateType::class, [
                'label' => 'Fin stage',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('timeCenter', IntegerType::class, [
                'label' => 'Heures centre',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('timeStage', IntegerType::class, [
                'label' => 'Heures stage',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('typeFormation', EntityType::class, [
                'class' => TypeFormation::class,
                'choice_label' => 'name',
                'label' => 'Titre professionnel',
                'attr' => ['class' => 'form-select']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}