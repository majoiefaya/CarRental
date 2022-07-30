<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\VenteRepository;
use App\Repository\VoitureRepository;
use App\Repository\ReservationRepository;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/client')]
class ClientController extends AbstractController
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params=$params;
    }

    
    #[Route('/', name: 'Client')]
    public function Acceuil(ClientRepository $clientRepository,RequestStack $requestStack): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT', null, "Erreur d'acces 404");
        return $this->render('client/AcceuilClient.html.twig', [
            'clients' => $clientRepository->findAllClientUnDelete(),
        ]);
    }



    #[Route('/AjouterUnClient', name: 'AjouterClient')]
    public function AjouterClient(Request $request, ClientRepository $clientRepository,UserPasswordHasherInterface $passwordhash,RequestStack $requestStack): Response
    {
        $client = new Client();
        $clients=$clientRepository->findAll();
        $Tel=$request->request->get('phone');
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $date=new \DateTime('@'.strtotime('now'));
        if ($form->isSubmitted() && $form->isValid()) {
            foreach($clients as $Client){
                if($Client->getUserName()==$client->getUserName()){
                    $error=$client->getUserName();
                    return $this->renderForm('client/AjouterClient.html.twig', [
                        'error'=>$error,
                        'client' => $client,
                        'form' => $form,
                        'clients' => $clientRepository->findAll()
                    ]);
                }
                else if($Client->getPassword()==$client->getPassword()){
                    $error=$client->getPassword();
                    return $this->renderForm('client/AjouterClient.html.twig', [
                        'error'=>$error,
                        'client' => $client,
                        'form' => $form,
                        'clients' => $clientRepository->findAll()
                    ]);
                }
                else if($Client->getEmail()==$client->getEmail()){
                    $error=$client->getEmail();
                    return $this->renderForm('client/AjouterClient.html.twig', [
                        'error'=>$error,
                        'client' => $client,
                        'form' => $form,
                        'clients' => $clientRepository->findAll()
                    ]);
                }

            }
            $hashdePassword=$passwordhash->hashPassword($client,$client->getPassword());
            $webpath=$this->params->get("kernel.project_dir").'/public/ClientsImages/';
            $chemin=$webpath.$_FILES['client']["name"]["image"];
            $destination=move_uploaded_file($_FILES['client']['tmp_name']['image'],$chemin);
            $client->setimage($_FILES['client']['name']['image']);
            if(!$user==null){
                $client->setCreerPar($username); 
            }else{
                $client->setCreerPar("Le Compte a été créer par Le Client");
            }
            $client->setCreerLe($date);
            $client->setEnable(True);
            $client->setTelephone($Tel);
            $client->setNomRole("Client");
            $client->setRoles(["ROLE_CLIENT"]);
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




    #[Route('/Rechercher_un_clientDateNaiss', name: 'RechercheDateNaiss')]
    public function RechercheDateNaiss(Request $request, ClientRepository $clientRepository): Response
    {
        $date1=$request->request->get('date1');
        $date2=$request->request->get('date2');
        $client=$clientRepository->findDateNaiss($date1,$date2);
        if (!$client==null) {
            return  $this->render('client/ResultatRecherche.html.twig', [
            'clients' => $client

            ]);
        }
        else{
            return  $this->render('client/ResultatRecherche.html.twig');
        }
        return  $this->redirectToRoute('Client');
    }




    #[Route('/Rechercher_un_clientCniNomUsername', name: 'RechercheClient')]
    public function RechercheClient(Request $request, ClientRepository $clientRepository): Response
    {
        $username=$request->request->get('RechercheUsername');
        $name=$request->request->get('Recherchenom');
        $cni=$request->request->get('cni');

        if($clientRepository->findBy(["cni"=>$cni]) or $clientRepository->findBy(["nom"=>$name]) or $clientRepository->findBy(["UserName"=>$username])){
             if($clientRepository->findBy(["cni"=>$cni])){
                $client =$clientRepository->findBy(["cni"=>$cni]);
                return  $this->render('client/ResultatRecherche.html.twig', [
                    'clients' => $client
    
                ]);
             }else if($clientRepository->findBy(["nom"=>$name])){
                $client =$clientRepository->findBy(["nom"=>$name]);
                return  $this->render('client/ResultatRecherche.html.twig', [
                    'clients' => $client
    
                ]);
             }else if($clientRepository->findBy(["UserName"=>$username])){
                $client =$clientRepository->findBy(["UserName"=>$username]);
                return  $this->render('client/ResultatRecherche.html.twig', [
                    'clients' => $client
                ]);
             }
        }else{
            return  $this->render('client/ResultatRecherche.html.twig');
        }
      

         return  $this->redirectToRoute('Client');
    }



    #[Route('/InfosDuClientN/{id}', name: 'InfosClient')]
    public function InfosClient(Client $client,ClientRepository $clientRepository,VoitureRepository $voitureRepository,ReservationRepository $reservationRepository,VenteRepository $venteRepository): Response
    {
        return $this->render('client/InformationsClient.html.twig', [
            'client' => $client,
            'NombreClient'=> $clientRepository->GetClientNumber(),
            'NombreVoiture' => $voitureRepository->GetCarNumber(),
            'NombreReservation'=>$reservationRepository->GetReservationNumber(),
            'NombreVente'=>$venteRepository->GetVenteNumber()
        ]);
    }



    #[Route('/{id}/ModifierUnClient', name: 'ModifierClient')]
    public function ModifierClient(Request $request, Client $client, ClientRepository $clientRepository,RequestStack $requestStack): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $date=new \DateTime('@'.strtotime('now'));
        if ($form->isSubmitted() && $form->isValid()) {
            $webpath=$this->params->get("kernel.project_dir").'/public/ClientsImages/';
            $chemin=$webpath.$_FILES['client']["name"]["image"];
            $destination=move_uploaded_file($_FILES['client']['tmp_name']['image'],$chemin);
            $client->setimage($_FILES['client']['name']['image']);
            $client->setModifierPar($username); 
            $client->setModifierLe($date);
            $clientRepository->add($client);
            return $this->redirectToRoute('Client');
        }

        return $this->renderForm('client/ModifierClient.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }




    #[Route('/supprimerUnClient/{id}', name: 'SupprimerClient')]
    public function SupprimerClient(Client $client, ClientRepository $clientRepository,EntityManagerInterface $manager): Response
    {
            $client->setEnable(False);
            $manager->persist($client);
            $manager->flush();
            return $this->redirectToRoute('Client');
    }



}
