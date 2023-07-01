<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Competence;
use App\Entity\Ligne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const DATAS = [
        [
            'libelle' => 'Compétences',
            'position' => 0,
            'actif' => true,
            'enfants' => [
                [
                    'libelle' => 'Langages',
                    'position' => 0,
                    'actif' => true,
                    'enfants' => [
                        [
                            'libelle' => 'Back-end',
                            'position' => 0,
                            'actif' => true,
                            'lignes' => [
                                [
                                    'titre' => 'PHP',
                                    'contenu' => 'Je pratique ce langage depuis maintenant 3ans',
                                    'position' => 0,
                                    'note' => 85,
                                    'actif' => true
                                ], [
                                    'titre' => 'SQL',
                                    'contenu' => 'L\'incontournable',
                                    'position' => 1,
                                    'note' => 80,
                                    'actif' => true
                                ]
                            ]
                        ], [
                            'libelle' => 'Front-end',
                            'position' => 1,
                            'actif' => true,
                            'lignes' => [
                                [
                                    'titre' => 'CSS / SCSS',
                                    'contenu' => 'Je ne suis pas designer mais je connais bien les possibilités de ce langage et ses enjeux pour un site web',
                                    'position' => 0,
                                    'note' => 70,
                                    'actif' => true
                                ], [
                                    'titre' => 'Javascript / Typescript',
                                    'contenu' => 'Bien qu\'ayant envie de me perfectionner sur les nouveautés ES6+, j\'arrives toujours à mes fins avec ce langage',
                                    'position' => 75,
                                    'actif' => true
                                ], [
                                    'titre' => 'HTML',
                                    'contenu' => '<strong>SEO friendly</strong>',
                                    'position' => 90,
                                    'actif' => true
                                ]
                            ]
                        ], [
                            'libelle' => 'Autres',
                            'position' => 2,
                            'actif' => true,
                            'lignes' => [
                                [
                                    'titre' => 'Script bash',
                                    'position' => 0,
                                    'actif' => true
                                ], [
                                    'titre' => 'Git',
                                    'contenu' => 'Un incontournable du métier',
                                    'position' => 1,
                                    'note' => 83,
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
                        [
                            'libelle' => 'PHP',
                            'position' => 0,
                            'actif' => true,
                            'lignes' => [
                                [
                                    'titre' => 'Symfony',
                                    'contenu' => 'Mon environnement de travail préféré',
                                    'position' => 0,
                                    'note' => 78,
                                    'actif' => true
                                ]
                            ]
                        ], [
                            'libelle' => 'Javascript',
                            'position' => 1,
                            'actif' => true,
                            'lignes' => [
                                [
                                    'titre' => 'JQuery',
                                    'contenu' => 'Bien obligé de le connaitre mais je l\'évite de plus en plus',
                                    'position' => 0,
                                    'note' => 45,
                                    'actif' => true
                                ], [
                                    'titre' => 'Angular',
                                    'contenu' => 'Utiliser pendant mes études j\'ai encore quelques restes',
                                    'position' => 1,
                                    'note' => 55,
                                    'actif' => true
                                ], [
                                    'titre' => 'Bootstrap',
                                    'contenu' => 'Très bonne librairie bien qu\'un peu volumineuse',
                                    'position' => 3,
                                    'note' => 70,
                                    'actif' => true
                                ]
                            ]
                        ]
                    ]
                ], [
                    'libelle' => 'Linux',
                    'position' => 2,
                    'actif' => true,
                    'lignes' => [
                        [
                            'titre' => 'Apache2',
                            'contenu' => 'Installation, maintenance',
                            'position' => 0,
                            'note' => 90,
                            'actif' => true
                        ], [
                            'titre' => 'Mysql',
                            'contenu' => 'Installation, maintenance',
                            'position' => 1,
                            'actif' => true
                        ], [
                            'titre' => 'Gestion des DNS',
                            'contenu' => 'Une fois en possession d\'un nom de domaine bien évidement',
                            'position' => 2,
                            'actif' => true
                        ], [
                            'titre' => 'Let\'s encrypt',
                            'contenu' => 'Pouvoir passer en https (bien que la procédure soit très simple)',
                            'position' => 3,
                            'actif' => true
                        ], [
                            'titre' => 'PHP FPM',
                            'contenu' => 'Gagner en performances sans dépendre de apache',
                            'position' => 4,
                            'actif' => true
                        ], [
                            'titre' => 'Mise en place d\'un pare-feu',
                            'position' => 5,
                            'actif' => true
                        ], [
                            'titre' => 'Installation d\'un FTP / SFTP',
                            'position' => 6,
                            'actif' => true
                        ], [
                            'titre' => 'Gitlab / Gitlab-CI',
                            'contenu' => 'Mise ne place de déploiement automatique',
                            'position' => 7,
                            'actif' => true
                        ], [
                            'titre' => 'Docker',
                            'contenu' => 'Jamais utiliser dans le monde du travail',
                            'position' => 8,
                            'actif' => true
                        ]
                    ]
                ]
            ]
        ], [
            'libelle' => 'Formation',
            'position' => 1,
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
        ], [
            'libelle' => 'Expériences',
            'position' => 2,
            'actif' => true,
            'lignes' => [
                [
                    'titre' => 'Développeur web à Trésor du Patrimoine',
                    'contenu' => 'Près de 3ans de loyaux services',
                    'position' => 0,
                    'actif' => true
                ], [
                    'titre' => 'Intérim à la CPAM de Évreux',
                    'contenu' => 'Développeur d\'applications internes, principalement avec Symfony',
                    'position' => 1,
                    'actif' => true
                ], [
                    'titre' => 'Stage à la CPAM de Évreux',
                    'contenu' => 'Maintenir une application en Java Spring',
                    'position' => 2,
                    'actif' => true
                ]
            ]
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        // Pour chacune des catégories décrites
        foreach (self::DATAS as $data) {
            // On extrait et on sauvegarde l'objet
            $categorieMere = self::extractCategorie($data);
            $manager->persist($categorieMere);
        }

        $manager->flush();
    }

    private function extractCategorie(array $c): Categorie
    {
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

    private function extractLigne(array $l, Categorie $categorie): Ligne|Competence
    {
        if (!isset($l['note']) && !isset($l['contenu'])) {
            $ligne = new Ligne();
            $ligne
                ->setTitre($l['titre'])
                ->setPosition($l['position'])
                ->setActif($l['actif'])
                ->setCategorie($categorie);

            return $ligne;
        } else {
            $competence = new Competence();
            $competence
                ->setTitre($l['titre'])
                ->setPosition($l['position'])
                ->setActif($l['actif'])
                ->setCategorie($categorie);

            if (isset($l['contenu'])) {
                $competence->setContenu($l['contenu']);
            }

            if (isset($l['note'])) {
                $competence->setNote($l['note']);
            }

            return $competence;
        }
    }
}
