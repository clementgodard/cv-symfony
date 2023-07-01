<?php

namespace App\Controller\admin;

use App\Entity\Competence;
use App\Form\CompetenceType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/competence')]
class CompetenceController extends AbstractController
{
    #[Route('/{competence<\d+>}', name: 'formCompetence', methods: ['GET', 'POST'])]
    public function formCompetence(Request $request, EntityManagerInterface $entityManager, ?Competence $competence = null): Response
    {
        if ($competence === null) {
            $competence = new Competence();
        }

        $form = $this->createForm(CompetenceType::class, $competence);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Competence $competence */
            $competence = $form->getData();
            $entityManager->persist($competence);
            $entityManager->flush();

            $this->addFlash('success', 'La compétence ' . $competence->getTitre() . ' a été enregistrée');

            return $this->redirectToRoute('formCompetence', [
                'competence' => $competence->getId()
            ]);
        }

        return $this->render('cv/admin/form/competence.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{competence<\d+>}', name: 'deleteCompetence', methods: ['DELETE'])]
    public function deleteCompetence(Request $request, EntityManagerInterface $entityManager, Competence $competence): JsonResponse|RedirectResponse
    {
        if (!$request->isXmlHttpRequest()) {
            $this->addFlash('warning', 'Faire une requête AJAX pour supprimer');
            return $this->redirectToRoute('liste');
        }

        try {
            $entityManager->remove($competence);
            $entityManager->flush();

            return new JsonResponse('OK');
        } catch (Exception $e) {
            return new JsonResponse('Impossible de supprimer la compétence : '.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
