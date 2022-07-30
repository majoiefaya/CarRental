<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'Commentaire', methods: ['GET'])]
    public function AcceuilCommentaire(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/ListeCommentaires.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/AjouterUnCommentaire', name: 'AjouterCommentaire', methods: ['GET', 'POST'])]
    public function AjouterCommentaire(Request $request, CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = new Commentaire();
        $date=new \DateTime('@'.strtotime('now'));
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setDatePublication($date);
            $commentaire->setPersonne($user);
            $commentaire->setCreerPar($username);
            $commentaire->setCreerLe($date);
            $commentaireRepository->add($commentaire);
            return $this->redirectToRoute('Acceuil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/AjouterCommentaire.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/InfosDuCommmentaireN/{id}', name: 'InfosCommentaire', methods: ['GET'])]
    public function InfosCommentaire(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/InfosCommentaire.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/ModifierLeCommmentaireN/{id}', name: 'ModifierCommentaire', methods: ['GET', 'POST'])]
    public function ModifierCommentaire(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->add($commentaire);
            return $this->redirectToRoute('Commentaire', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/ModifierCommentaire.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/SupprimerLeCommentaireN/{id}', name: 'SupprimerCommentaire', methods: ['POST'])]
    public function SupprimerCommentaire(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $commentaireRepository->remove($commentaire);
        }

        return $this->redirectToRoute('Commentaire', [], Response::HTTP_SEE_OTHER);
    }
}
