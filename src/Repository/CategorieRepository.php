<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    /**
     * @return array<Categorie>|null
     */
    public function findAllRootByPosition(bool $showInactive = false): ?array
    {
        $qb = $this->createQueryBuilder('c');

        if (!$showInactive) {
            $qb->andWhere('c.actif = 1');
        }

        // On ne prend que les catégories mères, car elles contiennent déjà les catégories enfants
        $qb->andWhere('c.parent IS NULL')
            ->orderBy('c.position', 'asc');

        return $qb->getQuery()->getResult();
    }
}
