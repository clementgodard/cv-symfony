<?php

namespace App\Entity\Abstract;

use App\Entity\Categorie;
use App\Entity\Competence;
use App\Entity\Ligne;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'discriminator', type: 'string')]
#[DiscriminatorMap([
    'ligne' => Ligne::class,
    'competence' => Competence::class,
])]
abstract class AbstractLigne
{
    #[Id, GeneratedValue, Column(type: 'integer')]
    private ?int $id = null;

    #[Column(type: 'string')]
    private string $titre = '';

    #[Column(type: 'integer')]
    #[Assert\PositiveOrZero]
    private int $position = 0;

    #[Column(type: 'boolean')]
    private bool $actif = true;

    #[ManyToOne(targetEntity: Categorie::class, inversedBy: 'lignes')]
    #[JoinColumn(nullable: false)]
    private Categorie $categorie;

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

    public function getCategorie(): Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(Categorie $categorie): AbstractLigne
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getClass(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
