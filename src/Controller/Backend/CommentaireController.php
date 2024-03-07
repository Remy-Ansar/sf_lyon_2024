<?php

namespace App\Controller\Backend;

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

#[Route('/admin/commentaires', name: 'admin.commentaires')]
class CommentaireController extends AbstractController
{
    public function __construct(
        private CommentaireRepository $commentRepo,
        private EntityManagerInterface $em,
    ) {
    }


    #[Route('/{slug}', name: '.index', methods: ['GET'])]
    public function index(?Article $article): Response | RedirectResponse
    {
        if (!$article) {
            $this->addFlash('error', 'article non trouvé');

            return $this->redirectToRoute('admin.articles.index');
        }

        return $this->render('Backend/Commentaires/index.html.twig', [
            'commentaires' => $article->getCommentaires(),

        ]);
    }

    // #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    // public function create(Request $request, EntityManagerInterface $em): Response | RedirectResponse
    // {
    //     $commentaire = new Commentaire;

    //     $form = $this->createForm(CommentaireType::class, $commentaire);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $user = $this->getUser();

    //         $commentaire->setUser($user);

    //         $this->em->persist($commentaire);
    //         $this->em->flush();

    //         $this->addFlash('success', 'Votre commentaire a bien été ajouté');

    //         return $this->redirectToRoute('admin.commentaires.index');
    //     }

    //     return $this->render('Backend/Commentaires/create.html.twig', [
    //         'form' => $form
    //     ]);
    // }

    // #[Route('/{slug}/edit', name: '.edit', methods: ['GET', 'POST'])]
    // public function edit(?Commentaire $commentaire, ?Article $article, ?User $user, Request $request): Response | RedirectResponse
    // {
    //     if (!$commentaire) {
    //         $this->addFlash('error', 'Commentaire non trouvé');

    //         return $this->redirectToRoute('admin.commentaires.edit');
    //     }

    //     $form = $this->createForm(CommentaireType::class, $commentaire);

    //     $form->handleRequest($request);

    //     if ($form->isValid() && $form->isSubmitted()) {

    //         $this->em->persist($commentaire);
    //         $this->em->flush();

    //         $this->addFlash('success', 'Votre commentaire a été modifié avec succès');

    //         return $this->redirectToRoute('admin.commentaire.index');
    //     }
    //     return $this->render('Backend/Commentaires/edit.html.twig', [
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}/delete', '.delete', methods: ['POST'])]
    public function delete(Request $request, ?Commentaire $commentaire): RedirectResponse
    {
        if (!$commentaire) {
            $this->addFlash('error', 'Commentaire non trouvé');

            return $this->redirectToRoute('app.articles.index');
        }

        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('token'))) {
            $this->em->remove($commentaire);
            $this->em->flush();

            $this->addFlash('success', 'Commentaire supprimé avec succès');
        } else {
            $this->addFlash('error', ' Token CSRF invalide');
        }

        return  $this->redirectToRoute('app.articles.show');
    }
}
