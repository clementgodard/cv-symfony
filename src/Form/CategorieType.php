<?php

namespace App\Form;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Libellé'
            ])
//            ->add('position', NumberType::class, [
//                'label' => 'Position',
//                'html5' => true
//            ])
            ->add('actif', ChoiceType::class, [
                'expanded' => true,
                'choices' => [
                    'Non' => false,
                    'Oui' => true
                ]
            ])
            ->add('parent', ChoiceType::class, [
                'label' => 'Catégorie parente',
                'choices' => $this->entityManager->getRepository(Categorie::class)->findAll(),
                'choice_label' => 'libelle',
                'placeholder' => 'Aucune',
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class
        ]);
    }
}
