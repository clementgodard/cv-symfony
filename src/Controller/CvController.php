<?php

namespace App\Controller;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CvController extends AbstractController
{
    #[Route('/', name: 'cv')]
    public function index(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Categorie::class)->findAllRootActiveByPosition();

        return $this->render('cv/cv.html.twig', [
            'categories' => $categories,
        ]);
    }

    // TODO: Faire une route "contactez-moi" avec un captcha et un envoi de mail via symfony sur mon adresse mail perso
}
