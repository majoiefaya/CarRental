<?php

namespace App\Controller;

use App\Entity\Modele;
use App\Form\ModeleType;
use App\Repository\ModeleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/modele')]
class ModeleController extends AbstractController
{
    #[Route('/', name: 'Modele', methods: ['GET'])]
    public function index(ModeleRepository $modeleRepository): Response
    {
        return $this->render('modele/AcceuilModele.html.twig', [
            'modeles' => $modeleRepository->findAll(),
        ]);
    }

    #[Route('/AjouterUnModel', name: 'AjouterModele', methods: ['GET', 'POST'])]
    public function AjouterModele(Request $request, ModeleRepository $modeleRepository): Response
    {
        $modele = new Modele();
        $form = $this->createForm(ModeleType::class, $modele);
        $form->handleRequest($request);
        $date=new \DateTime('@'.strtotime('now'));
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $modele->setCreerPar($username);
            $modele->setCreerLe($date);
            $modele->setEnable(True);
            $modeleRepository->add($modele);
            return $this->redirectToRoute('Modele', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('modele/AjouterModele.html.twig', [
            'modele' => $modele,
            'form' => $form,
        ]);
    }

    #[Route('/InfosDuModeleN/{id}', name: 'InfosModele', methods: ['GET'])]
    public function InfosModele(Modele $modele): Response
    {
        return $this->render('modele/InfosModele.html.twig', [
            'modele' => $modele,
        ]);
    }

    #[Route('/ModifierLeModeleN/{id}', name: 'ModifierModele', methods: ['GET', 'POST'])]
    public function ModifierModele(Request $request, Modele $modele, ModeleRepository $modeleRepository): Response
    {
        $form = $this->createForm(ModeleType::class, $modele);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modeleRepository->add($modele);
            return $this->redirectToRoute('Modele', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('modele/ModifierModele.html.twig', [
            'modele' => $modele,
            'form' => $form,
        ]);
    }

    #[Route('/SupprimerLeModeleN/{id}', name: 'SupprimerDefModele', methods: ['POST'])]
    public function SupprimerModele(Request $request, Modele $modele, ModeleRepository $modeleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$modele->getId(), $request->request->get('_token'))) {
            $modeleRepository->remove($modele);
        }

        return $this->redirectToRoute('Modele', [], Response::HTTP_SEE_OTHER);
    }
}
