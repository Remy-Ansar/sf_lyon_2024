<?php

namespace App\Controller\Frontend;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commentaires', name: 'app.commentaires')]
class CommentaireController extends AbstractController
{
    public function __construct(
        private CommentaireRepository $commentRepo,
        private EntityManagerInterface $em,
    ) {
    }


    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Frontend/Articles/index.html.twig', [
            'commentaires' => $this->commentRepo->findAll(),

        ]);
    }

    #[Route('/{slug}', name: '.show', methods: ['GET', 'POST'])]
    public function create(Request $request, ?Article $article, ?User $user, EntityManagerInterface $em): Response | RedirectResponse
    {
        $commentaire = new Commentaire;

        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $commentaire->setUser($user);

            $this->em->persist($commentaire);
            $this->em->flush();

            $this->addFlash('success', 'Votre commentaire a bien été ajouté');

            return $this->redirectToRoute('app.article.index');
        }

        return $this->render('Frontend/Articles/show.html.twig', [
            'article' => $article,
            'form' => $form
        ]);
    }

    // #[Route('/{slug}', name: '.show', methods: ['GET', 'POST'])]
    // public function edit(?Commentaire $commentaire, ?Article $article, ?User $user, Request $request): Response | RedirectResponse
    // {
    //     if (!$commentaire) {
    //         $this->addFlash('error', 'Commentaire non trouvé');

    //         return $this->redirectToRoute('app.articles.index');
    //     }

    //     $form = $this->createForm(CommentaireType::class, $commentaire);

    //     $form->handleRequest($request);

    //     if ($form->isValid() && $form->isSubmitted()) {

    //         $this->em->persist($commentaire);
    //         $this->em->flush();

    //         $this->addFlash('success', 'Votre commentaire a été modifié avec succès');

    //         return $this->redirectToRoute('app.article.index');
    //     }
    //     return $this->render('Frontend/Articles/show.html.twig', [
    //         'article' => $article,
    //         'form' => $form
    //     ]);
    // }
}
