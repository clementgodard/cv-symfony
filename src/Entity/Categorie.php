<?php

namespace App\Entity;

use App\Entity\Abstract\AbstractLigne;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

#[Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[Id, GeneratedValue, Column(type: 'integer')]
    private int $id;

    #[Column(type: 'string')]
    private string $libelle;

    #[Column(type: 'integer')]
    private int $position = 0;

    #[Column(type: 'boolean')]
    private bool $actif = true;

    #[ManyToOne(targetEntity: Categorie::class, inversedBy: 'categorieEnfant')]
    private ?Categorie $parent = null;

    #[OneToMany(mappedBy: 'parent', targetEntity: Categorie::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[OrderBy(['position' => 'ASC'])]
    private ?Collection $categorieEnfant = null;

    #[OneToMany(mappedBy: 'categorie', targetEntity: AbstractLigne::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[OrderBy(['position' => 'ASC'])]
    private ?Collection $lignes = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Categorie
    {
        $this->id = $id;

        return $this;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): Categorie
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): Categorie
    {
        $this->position = $position;

        return $this;
    }

    public function isActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): Categorie
    {
        $this->actif = $actif;

        return $this;
    }

    public function getParent(): ?Categorie
    {
        return $this->parent;
    }

    public function setParent(?Categorie $parent): Categorie
    {
        $this->parent = $parent;

        return $this;
    }

    public function getLignes(): ?Collection
    {
        return $this->lignes;
    }

    public function setLignes(?Collection $lignes): Categorie
    {
        $this->lignes = $lignes;

        return $this;
    }

    public function getCategorieEnfant(): ?Collection
    {
        return $this->categorieEnfant;
    }

    public function setCategorieEnfant(?Collection $categorieEnfant): Categorie
    {
        $this->categorieEnfant = $categorieEnfant;

        /** @var Categorie $enfant */
        foreach ($this->categorieEnfant as $enfant) {
            $enfant->setParent($this);
        }

        return $this;
    }

    public function getFullPathLibelle(string $return = ''): string
    {
        if (null === $this->getParent()) {
            if ('' === $return) {
                return $this->libelle;
            } else {
                return $this->libelle.$return;
            }
        } else {
            return $this->getParent()->getFullPathLibelle(' > '.$this->getLibelle().$return);
        }
    }
}
