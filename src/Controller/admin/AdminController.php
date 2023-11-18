<?php

namespace App\Controller\admin;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin', methods: ['GET'])]
    public function renderAdmin(): Response
    {
        return $this->render('admin/admin.html.twig');
    }

    #[Route('/liste', name: 'liste', methods: ['GET'])]
    public function liste(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAllRootByPosition(true);
        $countRootCategories = $categorieRepository->count(['parent' => null]);

        return $this->render('admin/liste.html.twig', [
            'categories' => $categories,
            'nbRootCategories' => $countRootCategories,
        ]);
    }
}
