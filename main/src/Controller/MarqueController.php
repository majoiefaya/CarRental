<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use App\Repository\ModeleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[Route('/marque')]
class MarqueController extends AbstractController
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params=$params;
    }


    #[Route('/', name: 'Marque', methods: ['GET'])]
    public function AcceuilMarque(MarqueRepository $marqueRepository,ModeleRepository $modeleRepository): Response
    {
        return $this->render('marque/AcceuilMarque.html.twig', [
            'marques' => $marqueRepository->findAll(),
            'modeles' => $modeleRepository->findAll(),
        ]);
    }

    #[Route('/AjouterUneMarque', name: 'AjouterMarque', methods: ['GET', 'POST'])]
    public function AjouterMarque(Request $request, MarqueRepository $marqueRepository): Response
    {
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);
        $date=new \DateTime('@'.strtotime('now'));
        $user = $this->getUser();
        $username=$user->getUserIdentifier();

        if ($form->isSubmitted() && $form->isValid()) {
            $webpath=$this->params->get("kernel.project_dir").'/public/MarquesImages/';
            $chemin=$webpath.$_FILES['marque']["name"]["logo"];
            $destination=move_uploaded_file($_FILES['marque']['tmp_name']['logo'],$chemin);
            $marque->setlogo($_FILES['marque']['name']['logo']);
            $marque->setCreerPar($username);
            $marque->setCreerLe($date);
            $marque->setEnable(True);
            $marqueRepository->add($marque);
            return $this->redirectToRoute('Marque', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('marque/AjouterMarque.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    #[Route('/InfosDeLaMarqueN/{id}', name: 'InfosMarque', methods: ['GET'])]
    public function InfosMarque(Marque $marque): Response
    {
        return $this->render('marque/InfosMarque.html.twig', [
            'marque' => $marque,
        ]);
    }

    #[Route('/ModifierLaMarqueN/{id}', name: 'ModifierMarque', methods: ['GET', 'POST'])]
    public function ModifierMarque(Request $request, Marque $marque, MarqueRepository $marqueRepository): Response
    {
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);
        $date=new \DateTime('@'.strtotime('now'));
        $user = $this->getUser();
        $username=$user->getUserIdentifier();

        if ($form->isSubmitted() && $form->isValid()) {
            $webpath=$this->params->get("kernel.project_dir").'/public/MarquesImages/';
            $chemin=$webpath.$_FILES['marque']["name"]["logo"];
            $destination=move_uploaded_file($_FILES['marque']['tmp_name']['logo'],$chemin);
            $marque->setlogo($_FILES['marque']['name']['logo']);
            $marque->setModifierPar($username);
            $marque->setModifierLe($date);
            $marqueRepository->add($marque);
            return $this->redirectToRoute('Marque', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('marque/ModifierMarque.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    #[Route('/SupprimerLaMarqueN/{id}', name: 'SupprimerDefMarque', methods: ['POST'])]
    public function SupprimerMarque(Request $request, Marque $marque, MarqueRepository $marqueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marque->getId(), $request->request->get('_token'))) {
            $marqueRepository->remove($marque);
        }

        return $this->redirectToRoute('Marque', [], Response::HTTP_SEE_OTHER);
    }
}
