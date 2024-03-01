<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManager;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/admin/articles', 'admin.articles')]
class ArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepo,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Backend/Articles/index.html.twig', [
            'articles' => $this->articleRepo->findAll(),
        ]);
    }

    #[Route('/create', '.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response | RedirectResponse
    {
        $article = new Article;

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $article->setUser($user);

            $this->em->persist($article);
            $this->em->flush();

            $this->addFlash('success', 'Votre article a bien été ajouté');

            return $this->redirectToRoute('admin.articles.index');
        }

        return $this->render('Backend/Articles/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{slug}/edit', '.edit', methods: ['GET', 'POST'])]
    public function edit(?User $user, ?Article $article, Request $request): Response|RedirectResponse
    {
        if (!$article) {
            $this->addFlash('error', 'Article non trouvé');

            return $this->redirectToRoute('admin.articles.index');
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($article);
            $this->em->flush();

            $this->addFlash('success', 'Article modifié avec succès');

            return $this->redirectToRoute('admin.articles.index');
        }

        return $this->render('Backend/Articles/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', '.delete', methods: ['POST'])]
    public function delete(Request $request, ?Article $article): Response | RedirectResponse
    {
        if (!$article) {
            $this->addFlash('error', 'Article non trouvé');

            return $this->redirectToRoute('admin.articles.index');
        }

        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('token'))) {
            $this->em->remove($article);
            $this->em->flush();

            $this->addFlash('success', 'Article supprimé avec succès');
        } else {
            $this->addFlash('error', ' Token CSRF invalide');
        }

        return  $this->redirectToRoute('admin.articles.index');
    }

    #[Route('/{id}/switch', '.switch', methods: ['GET'])]
    public function switch(?Article $article): JsonResponse
    {
        if (!$article) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Article non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $article->setEnable(
            !$article->isEnable()
        );

        $this->em->persist($article);
        $this->em->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'visibility changed',
            'enable' => $article->isEnable(),
        ], Response::HTTP_CREATED);
    }
}
