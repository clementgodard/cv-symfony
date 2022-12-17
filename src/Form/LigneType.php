<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Ligne;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneType extends AbstractType
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => $this->entityManager->getRepository(Categorie::class)->findAll(),
                'choice_label' => 'libelle'
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu',
                'required' => false
            ])
            ->add('note', NumberType::class, [
                'label' => 'Note',
                'html5' => true,
                'required' => false
            ])
            ->add('position', NumberType::class, [
                'label' => 'Position',
                'html5' => true
            ])
            ->add('actif', ChoiceType::class, [
                'expanded' => true,
                'choices' => [
                    'Non' => false,
                    'Oui' => true
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ligne::class
        ]);
    }
}
