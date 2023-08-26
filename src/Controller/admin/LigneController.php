<?php

namespace App\Controller\admin;

use App\Entity\Abstract\AbstractLigne;
use App\Entity\Ligne;
use App\Form\LigneType;
use Doctrine\ORM\EntityManagerInterface;
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
    public function formLigne(Request $request, EntityManagerInterface $entityManager, Ligne $ligne = null): Response
    {
        if (null === $ligne) {
            $ligne = new Ligne();
        }

        $form = $this->createForm(LigneType::class, $ligne);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Ligne $ligne */
            $ligne = $form->getData();

            $entityManager->persist($ligne);
            $entityManager->flush();
            $this->addFlash('success', 'La ligne '.$ligne->getTitre().' a été enregistrée');

            return $this->redirectToRoute('formLigne', ['ligne' => $ligne->getId()]);
        }

        return $this->render('cv/admin/form/ligne.html.twig', [
            'form' => $form->createView(),
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

            return new JsonResponse('Ligne : '.$ligne->getTitre().' supprimé avec succès !');
        } catch (\Exception $e) {
            return new JsonResponse('Impossible de supprimer la ligne : '.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{ligne<\d+>}/{up<[01]>}', name: 'updateLignePosition', methods: ['PATCH'])]
    public function updateCompetencePosition(
        Request $request,
        EntityManagerInterface $entityManager,
        AbstractLigne $ligne,
        bool $up
    ): Response {
        if (!$request->isXmlHttpRequest()) {
            $this->addFlash('warning', 'Faire une requête AJAX');

            return $this->redirectToRoute('liste');
        }

        // TRI
        $lignes = $entityManager->getRepository(AbstractLigne::class)->findBy([
            'categorie' => $ligne->getCategorie(),
        ], [
            'position' => 'ASC',
        ]);

        foreach ($lignes as $i => $l) {
            if ($l->getPosition() !== $i) {
                $l->setPosition($i);
                $entityManager->persist($l);
            }
        }

        $entityManager->flush();

        // On suppose que les autres sont déjà dans le bon ordre
        $actualPosition = $ligne->getPosition();
        $newPostion = ($up ? $actualPosition + 1 : $actualPosition - 1);

        $ligneAtNewPosition = $entityManager->getRepository(AbstractLigne::class)->findOneBy([
            'position' => $newPostion,
            'categorie' => $ligne->getCategorie(),
        ]);

        if (null === $ligneAtNewPosition) {
            return new JsonResponse(
                'Aucune ligne trouvée à la position '.$newPostion.
                ' pour la catégorie '.$ligne->getCategorie()->getLibelle(),
                Response::HTTP_NOT_FOUND
            );
        }

        $ligne->setPosition($newPostion);
        $ligneAtNewPosition->setPosition($actualPosition);

        $entityManager->persist($ligne);
        $entityManager->persist($ligneAtNewPosition);
        $entityManager->flush();

        return new JsonResponse('ok');
    }
}
