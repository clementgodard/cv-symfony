<?php

namespace App\Form;

use App\Entity\Competence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompetenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu',
                'required' => false,
            ])
            ->add('note', NumberType::class, [
                'label' => 'Note',
                'html5' => true,
                'required' => false,
            ])
            ->add('dateDebut', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'required' => false,
            ]);
    }

    public function getParent(): string
    {
        return LigneType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Competence::class,
        ]);
    }
}
