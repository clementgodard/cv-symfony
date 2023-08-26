<?php

namespace App\Entity\Abstract;

use App\Entity\Categorie;
use App\Entity\Competence;
use App\Entity\Ligne;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;

// TODO: Use @Assert to constrain form fields

#[Entity]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'discriminator', type: 'string')]
#[DiscriminatorMap(['ligne' => Ligne::class, 'competence' => Competence::class])]
abstract class AbstractLigne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $titre = '';

    #[ORM\Column]
    private int $position = 0;

    #[ORM\Column]
    private bool $actif = true;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'lignes')]
    private ?Categorie $categorie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): AbstractLigne
    {
        $this->id = $id;

        return $this;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): AbstractLigne
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): AbstractLigne
    {
        $this->position = $position;

        return $this;
    }

    public function isActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): AbstractLigne
    {
        $this->actif = $actif;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): AbstractLigne
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getClass(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
