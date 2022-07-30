<?php

namespace App\Controller;

use App\Entity\Vente;
use App\Form\VenteType;
use App\Repository\VenteRepository;
use App\Repository\ClientRepository;
use App\Repository\VoitureRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/vente')]
class VenteController extends AbstractController
{
    #[Route('/', name: 'Ventes', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository,VenteRepository $venteRepository,ClientRepository $clientRepository,VoitureRepository $voitureRepository,RequestStack $requestStack): Response
    {
        $session=$requestStack->getSession();
        $nom=$session->get('nom');
        $prenom=$session->get('prenom');
        return $this->render('vente/AcceuilVente.html.twig', [
            'ventes' => $venteRepository->findAll(),
            'NombreClient'=> $clientRepository->GetClientNumber(),
            'NombreVoiture' => $voitureRepository->GetCarNumber(),
            'NombreReservation'=>$reservationRepository->GetReservationNumber(),
            'NombreVente'=>$venteRepository->GetVenteNumber(),
            'nom'=>$nom,
            'prenom'=>$prenom
        ]);
    }

    #[Route('/AjouterUneVente', name: 'AjouterVente')]
    public function AjouterVente(Request $request, VenteRepository $venteRepository,RequestStack $requestStack): Response
    {
        $vente = new Vente();
        $form = $this->createForm(VenteType::class, $vente);
        $form->handleRequest($request);
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $date=new \DateTime('@'.strtotime('now'));
        if ($form->isSubmitted() && $form->isValid()) {
            if($vente->getVoiture()->getStatut()=="Disponible"){
                $vente->setCreerPar($username); 
                $vente->setCreerLe($date);
                $vente->setEnable(True);
                $vente->getVoiture()->setStatut("Vendu");
                $vente->getVoiture()->setDateAchat($date);
                $venteRepository->add($vente);
                return $this->redirectToRoute('Ventes');
            }else{
                return $this->redirectToRoute('Ventes');
            }
            
        }

        return $this->renderForm('vente/AjouterVente.html.twig', [
            'vente' => $vente,
            'form' => $form,
        ]);
    }

    #[Route('/InformationsDuneVente/{id}', name: 'InfosVente')]
    public function InfosVente(Vente $vente): Response
    {
        return $this->render('vente/InformationsVente.twig', [
            'vente' => $vente,
        ]);
    }

    #[Route('/{id}/ModifierUneVente', name: 'ModifierVente')]
    public function ModifierVente(Request $request, Vente $vente, VenteRepository $venteRepository,RequestStack $requestStack): Response
    {
        $form = $this->createForm(VenteType::class, $vente);
        $form->handleRequest($request);
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $date=new \DateTime('@'.strtotime('now'));
        if ($form->isSubmitted() && $form->isValid()) {
            $vente->setModifierPar($username); 
            $vente->setModifierLe($date);
            $venteRepository->add($vente);
            return $this->redirectToRoute('Ventes');
        }

        return $this->renderForm('vente/ModifierVente.html.twig', [
            'vente' => $vente,
            'form' => $form,
        ]);
    }

    #[Route('/RechercherUneVenteDate', name: 'RechercheVenteDate')]
    public function RechercheDateNaiss(Request $request, VenteRepository $venteRepository): Response
    {
        $date1=$request->request->get('date1');
        $date2=$request->request->get('date2');
        $ventes=$venteRepository->findDateNaiss($date1,$date2);
        if (!$ventes==null) {
            return  $this->render('vente/ResultatRecherche.html.twig', [
            'ventes' => $ventes

            ]);
        }
        else{
            return  $this->render('vente/ResultatRecherche.html.twig');
        }
        return  $this->redirectToRoute('Ventes');
    }


    #[Route('/SupprimerLaVente/{id}', name: 'SupprimerUneVente', methods: ['POST'])]
    public function delete(Vente $vente, VenteRepository $venteRepository,EntityManagerInterface $manager): Response
    {
        $vente->setEnable(False);
        $manager->persist($vente);
        $manager->flush();
        return $this->redirectToRoute('Ventes');
    }
}
