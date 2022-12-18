<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Ligne;
use App\Form\CategorieType;
use App\Form\LigneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin')]
    public function renderAdmin(): Response
    {
        return $this->render('cv/admin/admin.html.twig');
    }

    #[Route('/categorie/{categorie<\d+>}', name: 'categorie')]
    public function actionCategorie(Request $request, EntityManagerInterface $entityManager, ?Categorie $categorie = null): Response
    {
        // Nouvelle catégorie
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
            $this->addFlash('success', 'La catégorie ' . $categorie->getLibelle() . ' a été enregistrée');
            return $this->redirectToRoute('categorie', ['categorie' => $categorie->getId()]);
        }

        return $this->render('cv/admin/categorie.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ligne/{ligne<\d+>}', name: 'ligne')]
    public function actionLigne(Request $request, EntityManagerInterface $entityManager, ?Ligne $ligne = null): Response
    {
        // Nouvelle catégorie
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
            return $this->redirectToRoute('ligne', ['ligne' => $ligne->getId()]);
        }

        return $this->render('cv/admin/ligne.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/liste', name: 'liste')]
    public function liste(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findAllRootActiveByPosition();

        return $this->render('cv/admin/liste.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/ligneSupp/{ligne<\d+>}', name: 'ligneSupp')]
    public function ligneSupp(EntityManagerInterface $entityManager, Ligne $ligne): RedirectResponse
    {
        $entityManager->remove($ligne);
        $entityManager->flush();

        return $this->redirectToRoute('liste');
    }

    #[Route('/categorieSupp/{categorie<\d+>}', name: 'categorieSupp')]
    public function categorieSupp(EntityManagerInterface $entityManager, Categorie $categorie): RedirectResponse
    {
        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute('liste');
    }
}
