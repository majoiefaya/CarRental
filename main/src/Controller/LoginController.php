<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ResponsableRepository;
use App\Entity\Responsable;
use App\Form\ResponsableType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LoginController extends AbstractController
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params=$params;
    }


    #[Route('/', name: 'login')]
    public function Authentification(AuthenticationUtils $authenticationUtils): Response
    {
        $error=$authenticationUtils->getLastAuthenticationError();
        $LastUsername=$authenticationUtils->getLastUsername();
        return $this->render('login/Connexion.html.twig',[
            'error'=>$error,
            'LastUsername'=>$LastUsername
        ]);
    }

    #[Route('/CréerUnCompteAdmin', name: 'CréerCompte')]
    public function AjouterAuthUser(Request $request, ResponsableRepository $ResponsableRepository,RequestStack $requestStack,UserPasswordHasherInterface $passwordhash): Response
    {
        $responsable=new Responsable();
        $form = $this->createForm(ResponsableType::class, $responsable);
        $form->handleRequest($request);
        $session=$requestStack->getSession();
        $responsables=$ResponsableRepository->findAll();
        $date=new \DateTime('@'.strtotime('now'));
        if ($form->isSubmitted() && $form->isValid()) {
            $webpath=$this->params->get("kernel.project_dir").'/public/ResponsablesImages/';
            $chemin=$webpath.$_FILES['responsable']["name"]["image"];
            $destination=move_uploaded_file($_FILES['responsable']['tmp_name']['image'],$chemin);
            $responsable->setimage($_FILES['responsable']['name']['image']);
            $responsable->setCreerPar("MajoieFaya");
            $responsable->setCreerLe($date);
            $responsable->setEnable(True);
            $responsable->setRoles(["ROLE_ADMIN"]);
            $responsable->setNomRole("ADMIN");
            $hashdePassword=$passwordhash->hashPassword($responsable,$responsable->getpassword());
            $responsable->setpassword($hashdePassword);
            $ResponsableRepository->add($responsable);
            return $this->redirectToRoute('login');
        }

        return $this->renderForm('login/CréerCompte.html.twig', [
            'responsable' => $responsable,
            'form' => $form,
            'responsable' => $ResponsableRepository->findAll(),
            'Auth'=>$session
        ]);
    }

    #[Route('/AjouterUnClient', name: 'SignUpClient')]
    public function AjouterClient(Request $request, ClientRepository $clientRepository,UserPasswordHasherInterface $passwordhash,RequestStack $requestStack): Response
    {
        $client = new Client();
        $Tel=$request->request->get('phone');
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        $date=new \DateTime('@'.strtotime('now'));
        if ($form->isSubmitted() && $form->isValid()) {
            $hashdePassword=$passwordhash->hashPassword($client,$client->getPassword());
            $webpath=$this->params->get("kernel.project_dir").'/public/ClientsImages/';
            $chemin=$webpath.$_FILES['client']["name"]["image"];
            $destination=move_uploaded_file($_FILES['client']['tmp_name']['image'],$chemin);
            $client->setimage($_FILES['client']['name']['image']);
            $client->setCreerPar("Le Compte a été créer par Le Client");
            $client->setCreerLe($date);
            $client->setEnable(True);
            $client->setNomRole("Client");
            $client->setRoles(["ROLE_CLIENT"]);
            $client->setTelephone($Tel);
            $hashdePassword=$passwordhash->hashPassword($client,$client->getpassword());
            $client->setpassword($hashdePassword);
            $clientRepository->add($client);
            return $this->redirectToRoute('Client');
        }

        return $this->renderForm('client/AjouterClient.html.twig', [
            'client' => $client,
            'form' => $form,
            'clients' => $clientRepository->findAll(),
        ]);
    }


    
    #[Route('/SeDeconnecter', name: 'Deconnexion')]
    public function Deconnexion(RequestStack $requestStack): Response
    {
        $session= $requestStack->getSession();
        $session->set('nom',null);
        $session->set('prenom',null);
        $session->set('username',null);
        return $this->redirectToRoute('login');
    }

    #[Route('/Deconnexion', name: 'security_logout')]
    public function SecurityLogout(RequestStack $requestStack): Response
    {
        throw new \Exception('Désolée,Déconnexion échouée');
    }
}
