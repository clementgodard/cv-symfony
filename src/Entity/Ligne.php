<?php

namespace App\Entity;

use App\Entity\Abstract\AbstractLigne;
use App\Repository\LigneRepository;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: LigneRepository::class)]
class Ligne extends AbstractLigne
{
}
