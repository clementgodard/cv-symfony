<?php

namespace App\Entity;

use App\Entity\Abstract\AbstractLigne;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: CompetenceRepository::class)]
class Competence extends AbstractLigne
{
    public const NOTE_MAX = 100;

    #[Column(type: 'string', nullable: true)]
    private ?string $contenu = null;

    #[Column(type: 'float', nullable: true)]
    #[Assert\PositiveOrZero]
    #[Assert\LessThanOrEqual(value: Competence::NOTE_MAX, message: 'Une note ne peux pas dépasser '.Competence::NOTE_MAX)]
    private ?float $note;

    #[Column(type: 'datetime', nullable: true)]
    private ?\DateTime $dateDebut;

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): Competence
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): Competence
    {
        $this->note = $note;

        return $this;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTime $dateDebut): Competence
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }
}
