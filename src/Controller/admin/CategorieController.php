<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/{categorie<\d+>}', name: 'formCategorie', methods: ['GET', 'POST'])]
    public function formCategorie(Request $request, EntityManagerInterface $entityManager, Categorie $categorie = null): Response
    {
        if (null === $categorie) {
            $categorie = new Categorie();
        }

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Categorie $categorie */
            $categorie = $form->getData();
            $entityManager->persist($categorie);
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie '.$categorie->getLibelle().' a été sauvegardée');

            return $this->redirectToRoute('formCategorie', [
                'categorie' => $categorie->getId(),
            ]);
        }

        return $this->render('admin/form/categorie.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{categorie<\d+>}', name: 'deleteCategorie', methods: ['DELETE'])]
    public function deleteCategorie(EntityManagerInterface $entityManager, Categorie $categorie): JsonResponse
    {
        try {
            $entityManager->remove($categorie);
            $entityManager->flush();

            return new JsonResponse('Catégorie : '.$categorie->getLibelle().' supprimé avec succès !');
        } catch (\Exception $e) {
            return new JsonResponse('Impossible de supprimer la catégorie : '.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
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

        $categories = $entityManager->getRepository(Categorie::class)->findBy([
            'parent' => $categorie->getParent(),
        ], ['position' => 'ASC']);

        // Remet les positions en ordre à partir de 0
        foreach ($categories as $i => $category) {
            if ($category->getPosition() !== $i) {
                $category->setPosition($i);
                $entityManager->persist($category);
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

        if (null === $categorieAtNewPosition) {
            return new JsonResponse(
                'Aucune catégorie trouvée à la position '.$newPostion.' 
                pour la catégorie parente '.$categorie->getParent()->getLibelle(),
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
