<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin', methods: ['GET'])]
    public function renderAdmin(): Response
    {
        return $this->render('cv/admin/admin.html.twig');
    }

    #[Route('/liste', name: 'liste', methods: ['GET'])]
    public function liste(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findAllRootActiveByPosition();
        $countRootCategories = $entityManager->getRepository(Categorie::class)->count(['parent' => null]);

        return $this->render('cv/admin/liste.html.twig', [
            'categories' => $categories,
            'nbRootCategories' => $countRootCategories
        ]);
    }
}
