<?php

namespace App\Controller\Frontend;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/articles', name: 'app.articles')]
class ArticleController extends AbstractController
{
    #[Route('', name: '.index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepo): Response
    {
        return $this->render('Frontend/Articles/index.html.twig', [
            'articles' => $articleRepo->findAllOrderByDate(),
        ]);
    }
    #[Route('/{slug}', '.show', methods: ['GET'])]
    public function show(?Article $article): Response | RedirectResponse
    {
        if (!$article) {
            $this->addFlash('error', 'Article non trouvé');

            return $this->redirectToRoute('app.articles.index');
        }

        return $this->render('Frontend/Articles/show.html.twig', [
            'article' => $article
        ]);
    }
}
