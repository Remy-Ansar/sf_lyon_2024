<?php

namespace App\Controller\Frontend;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/articles', name: 'app.articles')]
class ArticleController extends AbstractController
{
    public function __construct(
        private CommentaireRepository $commentRepo,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepo): Response
    {
        return $this->render('Frontend/Articles/index.html.twig', [
            'articles' => $articleRepo->findAllOrderByDate(),
        ]);
    }
    #[Route('/{slug}', '.show', methods: ['GET', 'POST'])]
    public function show(?Article $article, Request $request): Response | RedirectResponse
    {
        if (!$article) {
            $this->addFlash('error', 'Article non trouvé');

            return $this->redirectToRoute('app.articles.index');
        }

        $commentaire = new Commentaire;

        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commentaire
                ->setUser($this->getUser())
                ->setArticle($article)
                ->setEnable(true);

            $this->em->persist($commentaire);
            $this->em->flush();

            $this->addFlash('success', 'Votre commentaire a bien été ajouté');

            return $this->redirectToRoute('app.articles.show', [
                'slug' => $article->getSlug()
            ]);
        }

        return $this->render('Frontend/Articles/show.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }
}
