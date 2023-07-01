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
use ReflectionClass;


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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return AbstractLigne
     */
    public function setId(?int $id): AbstractLigne
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     * @return AbstractLigne
     */
    public function setTitre(string $titre): AbstractLigne
    {
        $this->titre = $titre;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return AbstractLigne
     */
    public function setPosition(int $position): AbstractLigne
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActif(): bool
    {
        return $this->actif;
    }

    /**
     * @param bool $actif
     * @return AbstractLigne
     */
    public function setActif(bool $actif): AbstractLigne
    {
        $this->actif = $actif;
        return $this;
    }

    /**
     * @return Categorie|null
     */
    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    /**
     * @param Categorie|null $categorie
     * @return AbstractLigne
     */
    public function setCategorie(?Categorie $categorie): AbstractLigne
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getClass(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }
}
