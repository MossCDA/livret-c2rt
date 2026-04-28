<?php

namespace App\Form;

use App\Entity\Ecf;
use App\Entity\Skill;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EcfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('grade', ChoiceType::class, [
                'label' => 'Note',
                'choices' => [
                    'A — Acquis' => 'A',
                    'B — En cours' => 'B',
                    'C — Non acquis' => 'C',
                ],
                'placeholder' => '-- Choisir --',
                'attr' => ['class' => 'form-select']
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5
                ]
            ])
            ->add('skill', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'name',
                'label' => 'Compétence évaluée',
                'required' => false,
                'placeholder' => '-- Aucune --',
                'attr' => ['class' => 'form-select']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Ecf::class]);
    }
}