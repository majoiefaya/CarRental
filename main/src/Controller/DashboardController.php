<?php

namespace App\Controller;

use App\Repository\VenteRepository;
use App\Repository\VoitureRepository;
use App\Repository\ClientRepository;
use App\Repository\PersonneRepository;
use App\Repository\GerantRepository;
use App\Repository\ReservationRepository;
use App\Repository\CommentaireRepository;
use App\Repository\ResponsableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;

class DashboardController extends AbstractController
{
    #[Route('/Acceuil', name: 'Acceuil')]
    public function index(CommentaireRepository $commentaireRepository,VenteRepository $venteRepository,ClientRepository $clientRepository,VoitureRepository $voitureRepository,ReservationRepository $reservationRepository,RequestStack $requestStack): Response
    {
        $utilisateur = $this->getUser();
        $username=$utilisateur->getUserIdentifier();
        if ($username=null){
            return $this->redirectToRoute('Authentification');
        }
        return $this->render('dashboard/AcceuilIndex.html.twig', [
            'controller_name' => 'DashboardController',
            'clients' => $clientRepository->findAll(),
            'voitures' => $voitureRepository->findAll(),
            'NombreClient'=> $clientRepository->GetClientNumber(),
            'NombreVoiture' => $voitureRepository->GetCarNumber(),
            'NombreReservation'=>$reservationRepository->GetReservationNumber(),
            'NombreVente'=>$venteRepository->GetVenteNumber(),
            'commentaires'=>$commentaireRepository->findAll()
        ]);
        
    }

    #[Route('/APropos', name: 'APropos')]
    public function APropos(): Response
    {
        return $this->render('dashboard/APropos.html.twig');
    }

    #[Route('/Contacts', name: 'Contact')]
    public function Contacter(ResponsableRepository $responsableRepository,GerantRepository $gerantRepository): Response
    {
        return $this->render('dashboard/Contacts.html.twig',[
            'responsables'=>$responsableRepository->findAllResponsableUnDelete(),
            'gerants'=>$gerantRepository->findAllGerantUnDelete()
        ]);
    }

    #[Route('/InformationsDeCompte', name: 'UserInfos', methods: ['GET'])]
    public function UserInfos(PersonneRepository $personneRepository,ResponsableRepository $responsableRepository,ClientRepository $clientRepository,GerantRepository $gerantRepository): Response
    {
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        if($responsableRepository->findOneBy(["UserName"=>$username])){
            return $this->render('responsable/InfosResponsable.html.twig', [
                'responsable' => $responsableRepository->findOneBy(["UserName"=>$username]),
            ]);
        }elseif($clientRepository->findOneBy(["UserName"=>$username])){
            return $this->render('client/InformationsClient.html.twig', [
                'client' => $clientRepository->findOneBy(["UserName"=>$username]),
            ]);
        }elseif($gerantRepository->findOneBy(["UserName"=>$username])){
            return $this->render('gerant/InfosGerant.html.twig', [
                'gerant' => $gerantRepository->findOneBy(["UserName"=>$username]),
            ]);
        }
       
       
    }

}
