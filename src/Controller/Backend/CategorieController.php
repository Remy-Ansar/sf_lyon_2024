<?php

namespace App\Controller\Backend;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManager;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/categories', 'admin.categories')]
class CategorieController extends AbstractController
{
    public function __construct(
        private CategorieRepository $categorieRepo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Backend/Categories/index.html.twig', [
            'categories' => $this->categorieRepo->findAll(),
        ]);
    }

    #[Route('/create', '.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response | RedirectResponse
    {
        $categorie = new Categorie;

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($categorie);
            $this->em->flush();

            $this->addFlash('success', 'Votre catégorie a bien été ajoutée');

            return $this->redirectToRoute('admin.categories.index');
        }

        return $this->render('Backend/Categories/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/edit', '.edit', methods: ['GET', 'POST'])]
    public function edit(?Categorie $categorie, Request $request): Response | RedirectResponse
    {
        if (!$categorie) {
            $this->addFlash('error', 'Cette catégorie n\'existe pas');

            return $this->redirectToRoute('admin.categories.index');
        }

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($categorie);
            $this->em->flush();

            $this->addFlash('success', 'Votre catégorie a été modifiée');

            return $this->redirectToRoute('admin.categories.index');
        }

        return $this->render('Backend/Categories/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', '.delete', methods: ['POST'])]
    public function delete(Request $request, ?Categorie $categorie): Response | RedirectResponse
    {
        if (!$categorie) {
            $this->addFlash('error', 'Cette catégorie n\'existe pas');

            return $this->redirectToRoute('admin.categories.index');
        }

        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('token'))) {
            $this->em->remove($categorie);
            $this->em->flush();

            $this->addFlash('success', 'Catégorie supprimée avec succès');
        } else {
            $this->addFlash('error', 'Token CSRF invalide');
        }

        return $this->redirectToRoute('admin.categories.index');
    }

    #[Route('/{id}/switch', '.switch', methods: ['GET'])]
    public function switch(?Categorie $categorie): JsonResponse
    {
        if (!$categorie) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Catégorie non trouvée'
            ], Response::HTTP_NOT_FOUND);
        }

        $categorie->setEnable(
            !$categorie->isEnable()
        );

        $this->em->persist($categorie);
        $this->em->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'visibility changed',
            'enable' => $categorie->isEnable(),
        ], Response::HTTP_CREATED);
    }
}
