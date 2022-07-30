<?php

namespace App\Controller;

use App\Entity\Responsable;
use App\Form\ResponsableType;
use App\Repository\ResponsableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/responsable')]
class ResponsableController extends AbstractController
{
 
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params=$params;
    }


    #[Route('/', name: 'Responsable')]
    public function AcceuilResponsable(ResponsableRepository $responsableRepository,RequestStack $requestStack): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous avez essayer d'acceder a une page qui n'est destinée qu'aux Admins");
        
        return $this->render('responsable/AcceuilResponsable.html.twig', [
            'responsables' => $responsableRepository->findAllResponsableUnDelete(),
        ]);
    }

    #[Route('/CreationCompteResponsable', name: 'AjouterResponsable')]
    public function AjouterResponsable(Request $request, ResponsableRepository $responsableRepository,RequestStack $requestStack): Response
    {
        $responsable = new Responsable();
        $form = $this->createForm(ResponsableType::class, $responsable);
        $form->handleRequest($request);
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $date=new \DateTime('@'.strtotime('now'));
        if ($form->isSubmitted() && $form->isValid()) {
            $webpath=$this->params->get("kernel.project_dir").'/public/ResponsablesImages/';
            $chemin=$webpath.$_FILES['responsable']["name"]["image"];
            $destination=move_uploaded_file($_FILES['responsable']['tmp_name']['image'],$chemin);
            $responsable->setimage($_FILES['responsable']['name']['image']);
            $responsable->setEnable(True);
            $responsable->setNomRole("Responsable");
            if($username!=null){
                $responsable->setCreerPar($username); 
                $responsable->setCreerLe($date);
                $responsableRepository->add($responsable);
                return $this->redirectToRoute('Responsable');
            }
            else{
                $responsableRepository->add($responsable);
                return $this->redirectToRoute('Authentification');

            }
           
           
        }

        

        return $this->renderForm('responsable/AjouterResponsable.html.twig', [
            'responsable' => $responsable,
            'form' => $form,
        ]);
    }

    #[Route('/InfosResponsable/{id}', name: 'InfosResponsable', methods: ['GET'])]
    public function InfosResponsable(Responsable $responsable): Response
    {
        return $this->render('responsable/InfosResponsable.html.twig', [
            'responsable' => $responsable,
        ]);
    }

    #[Route('/ModifierInfosResponsable/{id}', name: 'ModifierResponsable', methods: ['GET', 'POST'])]
    public function ModifierResponsable(Request $request, Responsable $responsable, ResponsableRepository $responsableRepository,RequestStack $requestStack): Response
    {
        $form = $this->createForm(ResponsableType::class, $responsable);
        $form->handleRequest($request);
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $date=new \DateTime('@'.strtotime('now'));
        if ($form->isSubmitted() && $form->isValid()) {
            $responsable->setModifierPar($username); 
            $responsable->setModifierLe($date);
            $responsableRepository->add($responsable);
            return $this->redirectToRoute('Responsable');
        }

        return $this->renderForm('responsable/ModifierResponsable.html.twig', [
            'responsable' => $responsable,
            'form' => $form,
        ]);
    }

    #[Route('/AcceuilResponsable/{id}', name: 'SupprimerResponsable', methods: ['POST'])]
    public function SupprimerResponsable(Request $request, Responsable $responsable, ResponsableRepository $responsableRepository,EntityManagerInterface $manager): Response
    {
        $responsable->setEnable(False);
        $manager->persist($responsable);
        $manager->flush();
        return $this->redirectToRoute('Responsable');
    }
}
