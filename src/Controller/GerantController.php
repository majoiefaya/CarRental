<?php

namespace App\Controller;

use App\Entity\Gerant;
use App\Form\GerantType;
use App\Repository\GerantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/gerant')]
class GerantController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params=$params;
    }


    #[Route('/', name: 'Gerant', methods: ['GET'])]
    public function AcceuilGerant(GerantRepository $gerantRepository,RequestStack $requestStack): Response
    {
        $this->denyAccessUnlessGranted('ROLE_GERANT', null, "Vous avez essayer d'acceder a une page qui n'est destinée qu'aux Gerants");

        return $this->render('gerant/AcceuilGerant.html.twig', [
            'gerants' => $gerantRepository->findAllGerantUnDelete(),
        ]);
    }

    #[Route('/CreerUnCompteGerant', name: 'AjouterGerant', methods: ['GET', 'POST'])]
    public function AjoutGerant(Request $request, GerantRepository $gerantRepository,RequestStack $requestStack,UserPasswordHasherInterface $passwordhash): Response
    {
        $gerant = new Gerant();
        $form = $this->createForm(GerantType::class, $gerant);
        $form->handleRequest($request);
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $date=new \DateTime('@'.strtotime('now'));
        if ($form->isSubmitted() && $form->isValid()) {
            $webpath=$this->params->get("kernel.project_dir").'/public/GerantImages/';
            $chemin=$webpath.$_FILES['gerant']["name"]["image"];
            $destination=move_uploaded_file($_FILES['gerant']['tmp_name']['image'],$chemin);
            $gerant->setimage($_FILES['gerant']['name']['image']);
            $gerant->setCreerPar($username); 
            $gerant->setCreerLe($date);
            $gerant->setEnable(True);
            $gerant->setNomRole("Gerant");
            $gerant->setRoles(["ROLE_GERANT"]);
            $hashdePassword=$passwordhash->hashPassword($gerant,$gerant->getpassword());
            $gerant->setpassword($hashdePassword);
            $gerantRepository->add($gerant);
            $gerantRepository->add($gerant);
            return $this->redirectToRoute('Gerant');
        }

        return $this->renderForm('gerant/AjouterGerant.html.twig', [
            'gerant' => $gerant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'InfosGerant', methods: ['GET'])]
    public function InfosGerant(Gerant $gerant): Response
    {
        return $this->render('gerant/InfosGerant.html.twig', [
            'gerant' => $gerant,
        ]);
    }

    #[Route('/InfosAProposDe/{UserName}', name: 'InfosGerantByUsername', methods: ['GET','POST'])]
    public function InfosGerantByUsername(Gerant $gerant): Response
    {
            return $this->render('gerant/InfosGerant.html.twig', [
                'gerant' => $gerant
            ]);
    }


    #[Route('/ModifierLesInfosDuGerantN/{id}', name: 'ModifierGerant', methods: ['GET', 'POST'])]
    public function ModifierGerant(Request $request, Gerant $gerant, GerantRepository $gerantRepository,RequestStack $requestStack): Response
    {
        $form = $this->createForm(GerantType::class, $gerant);
        $form->handleRequest($request);
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $date=new \DateTime('@'.strtotime('now'));
        if ($form->isSubmitted() && $form->isValid()) {
            $gerant->setModifierPar($username); 
            $gerant->setModifierLe($date);
            $gerantRepository->add($gerant);
            return $this->redirectToRoute('Gerant');
        }

        return $this->renderForm('gerant/ModifierGerant.html.twig', [
            'gerant' => $gerant,
            'form' => $form,
        ]);
    }

    #[Route('/SupprimerLeCompteGerantN/{id}', name: 'SupprimerGerant', methods: ['POST'])]
    public function SupprimerGerant(Request $request, Gerant $gerant, GerantRepository $gerantRepository,EntityManagerInterface $manager): Response
    {
        $gerant->setEnable(False);
        $manager->persist($gerant);
        $manager->flush();
        return $this->redirectToRoute('Gerant');
    }
}
