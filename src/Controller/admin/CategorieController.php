<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/{categorie<\d+>}', name: 'formCategorie', methods: ['GET', 'POST'])]
    public function formCategorie(Request $request, EntityManagerInterface $entityManager, ?Categorie $categorie = null): Response
    {
        if ($categorie === null) {
            $categorie = new Categorie();
        }

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Categorie $categorie */
            $categorie = $form->getData();
            $entityManager->persist($categorie);
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie ' . $categorie->getLibelle() . ' a été sauvegardée');

            return $this->redirectToRoute('formCategorie', [
                'categorie' => $categorie->getId()
            ]);
        }

        return $this->render('cv/admin/form/categorie.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{categorie<\d+>}', name: 'deleteCategorie', methods: ['DELETE'])]
    public function deleteCategorie(Request $request, EntityManagerInterface $entityManager, Categorie $categorie): JsonResponse|RedirectResponse
    {
        if (!$request->isXmlHttpRequest()) {
            $this->addFlash('warning', 'Faire une requête AJAX pour supprimer');
            return $this->redirectToRoute('liste');
        }

        try {
            $entityManager->remove($categorie);
            $entityManager->flush();

            return new JsonResponse('OK');
        } catch (Exception $e) {
            return new JsonResponse('Impossible de supprimer la catégorie : '.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
