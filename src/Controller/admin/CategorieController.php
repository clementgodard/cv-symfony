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

            return new JsonResponse('Catégorie : ' . $categorie->getLibelle() . ' supprimé avec succès !');
        } catch (Exception $e) {
            return new JsonResponse('Impossible de supprimer la catégorie : ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id<\d+>}/{up<[01]>}', name: 'updateCategoriePosition', methods: ['PATCH'])]
    public function updateCategoriePosition(
        Request $request,
        EntityManagerInterface $entityManager,
        Categorie $categorie,
        bool $up,
    ): Response {
        if (!$request->isXmlHttpRequest()) {
            $this->addFlash('warning', 'Faire une requête AJAX');
            return $this->redirectToRoute('liste');
        }

        // TRI
        $categories = $entityManager->getRepository(Categorie::class)->findBy([
            'parent' => $categorie->getParent()
        ], [
            'position' => 'ASC'
        ]);

        foreach ($categories as $i => $c) {
            if ($c->getPosition() !== $i) {
                $c->setPosition($i);
                $entityManager->persist($c);
            }
        }
        $entityManager->flush();


        // On suppose que les autres sont déjà dans le bon ordre
        $actualPosition = $categorie->getPosition();
        $newPostion = ($up ? $actualPosition + 1 : $actualPosition - 1);

        $categorieAtNewPosition = $entityManager->getRepository(Categorie::class)->findOneBy([
            'position' => $newPostion,
            'parent' => $categorie->getParent(),
        ]);

        if ($categorieAtNewPosition === null) {
            return new JsonResponse(
                'Aucune catégorie trouvée à la position ' . $newPostion .
                ' pour la catégorie parente ' . $categorie->getParent()->getLibelle(),
                Response::HTTP_NOT_FOUND
            );
        }

        $categorie->setPosition($newPostion);
        $categorieAtNewPosition->setPosition($actualPosition);

        $entityManager->persist($categorie);
        $entityManager->persist($categorieAtNewPosition);
        $entityManager->flush();
        return new JsonResponse('ok');
    }
}
