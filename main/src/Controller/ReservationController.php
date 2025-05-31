<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ClientRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\VenteRepository;
use App\Entity\Vente;

#[Route('/Réservations')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'Réservation', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/AcceuilReservation.html.twig', [
            'reservations' => $reservationRepository->AllReservationUnSelled(),
        ]);
    }

    #[Route('/FaireUnReservation/{id}', name: 'FaireReservation', methods: ['GET'])]
    public function FaireReservation(ReservationRepository $reservationRepository,Voiture $voiture): Response
    {
        return $this->render('reservation/AjoutReservationClient.html.twig',[
            'voiture'=>$voiture
        ]);
    }

    #[Route('/RéserverLaVoitureN/{id}', name: 'ReserverVoiture', methods: ['GET', 'POST'])]
    public function Reserver(Request $request, ReservationRepository $reservationRepository,Voiture $voiture,ClientRepository $clientRepository): Response
    {
        $montant=$request->request->get('montant');
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $date=new \DateTime('@'.strtotime('now'));
        $client=$clientRepository->findOneBy(["UserName"=>$username]);
        $reservation = new Reservation();
        $voiture->setStatut("Reservé");
        $reservation->setVoiture($voiture);
        $reservation->setClient($client);
        $reservation->setDateReservation($date);
        $reservation->setPrixReservation($montant);
        $reservationRepository->add($reservation);
        if($user->getRoles()=="ROLE_GERANT"){
            return $this->redirectToRoute('Réservation', [], Response::HTTP_SEE_OTHER);
        }
        return $this->redirectToRoute('Voiture', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/AjouterUneNouvelleRéservation', name: 'AjouterReservation', methods: ['GET', 'POST'])]
    public function AjouterReservation(Request $request, ReservationRepository $reservationRepository): Response
    {
        $date=new \DateTime('@'.strtotime('now'));
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $voiture=$reservation->getVoiture();
        if ($form->isSubmitted() && $form->isValid()) {
            $voiture->setStatut("Reservé");
            $reservation->setDateReservation($date);
            $reservation->setCreerLe($date);
            $reservation->setCreerPar($username);
            $reservation->setEnable(True);
            $reservationRepository->add($reservation);
            return $this->redirectToRoute('Réservation');
        }

        return $this->renderForm('reservation/AjouterReservation.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/ValiderLaRéservationN/{id}', name: 'Vendre')]
    public function Vendre(VenteRepository $venteRepository,Request $request, ReservationRepository $reservationRepository,RequestStack $requestStack, Reservation $reservation): Response
    {
        $date=new \DateTime('@'.strtotime('now'));
        $client=$reservation->getClient();
        $voiture=$reservation->getVoiture();
        $voiture->setDateAchat($date);
        $montant=$reservation->getPrixReservation();
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $vente=new Vente();
        $vente->setClient($client);
        $vente->setVoiture($voiture);
        $vente->setDateVente($date);
        $vente->setMontant($montant);
        $vente->setCreerPar($username); 
        $vente->setCreerLe($date);
        $vente->setEnable(True);
        $vente->getVoiture()->setStatut("Vendu");
        $venteRepository->add($vente);
        return $this->redirectToRoute('Réservation', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/ModifierLaRéservationN/{id}', name: 'ModifierRéservation', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->add($reservation);
            return $this->redirectToRoute('Réservation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/ModifierReservation.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/SupprimerDefinitivementLaVoitureN/{id}', name: 'SupprimerDef', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
            $voiture=$reservation->getVoiture();
            $voiture->setStatut("Disponible");
            $reservationRepository->remove($reservation);
        

        return $this->redirectToRoute('Réservation', [], Response::HTTP_SEE_OTHER);
    }

    
    #[Route('/supprimerUnClient/{id}', name: 'SupprimerReservation')]
    public function SupprimerClient(Reservation $reservation,EntityManagerInterface $manager): Response
    {
            $reservation->setEnable(False);
            $manager->persist($reservation);
            $manager->flush();
            return $this->redirectToRoute('Réservation');
    }

}
