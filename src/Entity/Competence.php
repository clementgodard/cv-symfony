<?php

namespace App\Entity;

use App\Entity\Abstract\AbstractLigne;
use App\Repository\CompetenceRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompetenceRepository::class)]
class Competence extends AbstractLigne
{
    #[ORM\Column(nullable: true)]
    private ?string $contenu = null;

    #[ORM\Column(nullable: true)]
    private ?int $note;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $dateDebut;

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): Competence
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): Competence
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDateDebut(): ?DateTime
    {
        return $this->dateDebut;
    }

    /**
     * @param DateTime|null $dateDebut
     * @return Competence
     */
    public function setDateDebut(?DateTime $dateDebut): Competence
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }
}
