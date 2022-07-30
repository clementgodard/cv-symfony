<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Ligne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const DATA = [
        [
            'libelle' => 'Compétences',
            'position' => 0,
            'actif' => true,
            'enfants' => [
                [
                    'libelle' => 'Language',
                    'position' => 0,
                    'actif' => true,
                    'enfants' => [
                        [
                            'libelle' => 'Back end',
                            'position' => 0,
                            'actif' => true,
                            'lignes' => [
                                [
                                    'titre' => 'PHP',
                                    'contenu' => 'Je pratique ce language depuis maintenant 3ans',
                                    'position' => 0,
                                    'actif' => true
                                ], [
                                    'titre' => 'NodeJS',
                                    'contenu' => 'Ne me prenez surtout pas pour ce language',
                                    'position' => 1,
                                    'actif' => true
                                ]
                            ]
                        ], [
                            'libelle' => 'Front end',
                            'position' => 1,
                            'actif' => true,
                            'lignes' => [
                                [
                                    'titre' => 'CSS',
                                    'contenu' => 'Je ne suis pas designer mais je connais bien les possibilités de ce language et ses enjeux pour un site web',
                                    'position' => 1,
                                    'actif' => true
                                ], [
                                    'titre' => 'Javascript',
                                    'contenu' => 'Bien qu\'ayant envie de me perfectionner sur les nouveautés ES6+, j\'arrives toujours à mes fins avec ce language',
                                    'position' => 0,
                                    'actif' => true
                                ], [
                                    'titre' => 'HTML',
                                    'contenu' => '<strong>SEO friendly</strong>',
                                    'position' => 0,
                                    'actif' => true
                                ]
                            ]
                        ]
                    ]
                ], [
                    'libelle' => 'Framework',
                    'position' => 1,
                    'actif' => true,
                    'enfants' => [

                    ]
                ]
            ]
        ], [
            'libelle' => 'Fomation',
            'position' => 0,
            'actif' => true,
            'lignes' => [
                [
                    'titre' => 'Développeur Web et Web mobile',
                    'contenu' => 'Diplôme obtenu auprès de la Wild Code School à La Loupe',
                    'position' => 0,
                    'actif' => true
                ], [
                    'titre' => 'Systèmes informatique et réseaux',
                    'contenu' => 'BTS SNIR obtenu au lycée Modeste Leroy à Évreux',
                    'position' => 1,
                    'actif' => true
                ]
            ]
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        // Pour chacune des catégories décrites
        foreach (self::DATA as $c) {
            // On extrait et on sauvegarde l'objet
            $categorieMere = self::extractCategorie($c);
            $manager->persist($categorieMere);
        }

        $manager->flush();
    }

    private function extractCategorie(array $c): Categorie {
        $categorie = new Categorie();
        $categorie
            ->setLibelle($c['libelle'])
            ->setActif($c['actif'])
            ->setPosition($c['position']);

        if (isset($c['lignes'])) {
            $lignes = new ArrayCollection();

            foreach ($c['lignes'] as $l) {
                $lignes->add($this->extractLigne($l, $categorie));
            }

            $categorie->setLignes($lignes);
        }

        if (isset($c['enfants'])) {
            $enfants = new ArrayCollection();

            foreach ($c['enfants'] as $enfant) {
                $enfants->add($this->extractCategorie($enfant));
            }

            $categorie->setCategorieEnfant($enfants);
        }

        return $categorie;
    }

    private function extractLigne(array $l, Categorie $categorie): Ligne {
        $ligne = new Ligne();
        $ligne
            ->setTitre($l['titre'])
            ->setPosition($l['position'])
            ->setActif($l['actif'])
            ->setCategorie($categorie);

        if (isset($l['contenu'])) {
            $ligne->setContenu($l['contenu']);
        }

        return $ligne;
    }
}
