<?php

namespace App\Controller\admin;

use App\Entity\Ligne;
use App\Form\LigneType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/ligne')]
class LigneController extends AbstractController
{
    #[Route('/{ligne<\d+>}', name: 'formLigne', methods: ['GET', 'POST'])]
    public function formLigne(Request $request, EntityManagerInterface $entityManager, ?Ligne $ligne = null): Response
    {
        if ($ligne === null) {
            $ligne = new Ligne();
        }

        $form = $this->createForm(LigneType::class, $ligne);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Ligne $ligne */
            $ligne = $form->getData();

            $entityManager->persist($ligne);
            $entityManager->flush();
            $this->addFlash('success', 'La ligne ' . $ligne->getTitre() . ' a été enregistrée');
            return $this->redirectToRoute('formLigne', ['ligne' => $ligne->getId()]);
        }

        return $this->render('cv/admin/form/ligne.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{ligne<\d+>}', name: 'deleteLigne', methods: ['DELETE'])]
    public function deleteLigne(Request $request, EntityManagerInterface $entityManager, Ligne $ligne): JsonResponse|RedirectResponse
    {
        if (!$request->isXmlHttpRequest()) {
            $this->addFlash('warning', 'Faire une requête AJAX pour supprimer');
            return $this->redirectToRoute('liste');
        }

        try {
            $entityManager->remove($ligne);
            $entityManager->flush();

            return new JsonResponse('OK');
        } catch (Exception $e) {
            return new JsonResponse('Impossible de supprimer la ligne : '.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
