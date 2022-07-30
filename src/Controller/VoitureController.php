<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use App\Repository\ResponsableRepository;
use App\Repository\GerantRepository;
use App\Repository\ModeleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/voiture')]
class VoitureController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params=$params;
    }



    #[Route('/', name: 'Voiture')]
    public function VoitureAcceuil(VoitureRepository $voitureRepository,RequestStack $requestStack): Response
    {
        return $this->render('voiture/AcceuilVoiture.html.twig', [
            'voitures' => $voitureRepository->findAllVoitureUnDelete()
        ]);
    }

    #[Route('/CorbeilleVoitures', name: 'DeleteCarList')]
    public function VoitureCorbeille(VoitureRepository $voitureRepository,RequestStack $requestStack): Response
    {
        return $this->render('voiture/ListeVoituresSupprimes.html.twig', [
            'voitures' => $voitureRepository-> GetCarDelete()
        ]);
    }

    #[Route('/VoituresPubliéesParLeGerant', name: 'GerantPublications')]
    public function GerantPublications(VoitureRepository $voitureRepository,RequestStack $requestStack): Response
    {
        return $this->render('voiture/PublicationsGerants.html.twig', [
            'voitures' => $voitureRepository->findAllGerantCarPublication()
        ]);
    }


    #[Route('/RecupererLaVoitureN/{id}', name: 'RecupererVoiture')]
    public function RecupererVoiture(VoitureRepository $voitureRepository,RequestStack $requestStack, Voiture $voiture,EntityManagerInterface $manager): Response
    {
        $voiture->setEnable(True);
        $manager->persist($voiture);
        $manager->flush();
        return $this->redirectToRoute('Voiture');
    }


    #[Route('/AjouterUneVoiture', name: 'AjouterVoiture')]
    public function AjouterVoiture(GerantRepository $gerantRepository,ResponsableRepository $responsableRepository,Request $request, VoitureRepository $voitureRepository,RequestStack $requestStack): Response
    {
        $bytes = random_bytes(2);
        $id=bin2hex($bytes);
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $date=new \DateTime('@'.strtotime('now'));
        if ($form->isSubmitted() && $form->isValid()) {
            $webpath=$this->params->get("kernel.project_dir").'/public/CarImages/';
            $chemin=$webpath.$_FILES['voiture']["name"]["image"];
            $destination=move_uploaded_file($_FILES['voiture']['tmp_name']['image'],$chemin);
            $voiture->setimage($_FILES['voiture']['name']['image']);
            $voiture->setStatut('Disponible');
            $voiture->setCreerPar($username); 
            $voiture->setCreerLe($date);
            $voiture->setNumId($id);
            if ($responsableRepository->findOneBy(["UserName"=>$username])){
                $voiture->setEnable(True);
                $voitureRepository->add($voiture);
            }elseif($gerantRepository->findOneBy(["UserName"=>$username])){
                $voiture->setEnable(False);
                $voiture->setStatut('AjoutGerant');
                $voitureRepository->add($voiture);
            };
          
           
            return $this->redirectToRoute('Voiture');
        }

        return $this->renderForm('voiture/AjouterVoiture.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }



    #[Route('/Rechercher_une_Voiture', name: 'RechercheVoiture')]
    public function RechercheVoiture(Request $request, VoitureRepository $VoitureRepository,ModeleRepository $modeleRepository): Response
    {
        $Modele=$request->request->get('modele');
        $Marque=$request->request->get('marque');
        $numId=$request->request->get('numId');

       
             if($VoitureRepository->findCarByModel($Modele)){
                $voiture =$VoitureRepository->findCarByModel($Modele);
                return  $this->render('voiture/ResultatRecherche.html.twig', [
                    'voitures' => $voiture
    
                ]);
             }else if($VoitureRepository->findCarByMarque($Marque)){
                $voiture =$VoitureRepository->findCarByMarque($Marque);
                return  $this->render('voiture/ResultatRecherche.html.twig', [
                    'voitures' => $voiture,
    
                ]);
             }else if($VoitureRepository->findBy(["NumId"=>$numId])){
                $voiture =$VoitureRepository->findBy(["NumId"=>$numId]);
                return  $this->render('Voiture/ResultatRecherche.html.twig', [
                    'voitures' => $voiture,
    
                ]);
            
        }else{
            return  $this->render('voiture/ResultatRecherche.html.twig');
        }
      

         return  $this->redirectToRoute('Voiture');
    }




    #[Route('/InfosVoiture/{id}', name: 'InfosVoiture')]
    public function InfosVoiture(Voiture $voiture): Response
    {
        return $this->render('voiture/InformationsVoiture.html.twig', [
            'voiture' => $voiture,
        ]);
    }




    #[Route('/{id}/ModifierInformationsVoiture', name: 'ModifierVoiture')]
    public function ModifierVoiture(Request $request, Voiture $voiture, VoitureRepository $voitureRepository,RequestStack $requestStack): Response
    {
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);
        $user = $this->getUser();
        $username=$user->getUserIdentifier();
        $date=new \DateTime('@'.strtotime('now'));
        if ($form->isSubmitted() && $form->isValid()) {
            $webpath=$this->params->get("kernel.project_dir").'/public/CarImages/';
            $chemin=$webpath.$_FILES['voiture']["name"]["image"];
            $destination=move_uploaded_file($_FILES['voiture']['tmp_name']['image'],$chemin);
            $voiture->setimage($_FILES['voiture']['name']['image']);
            $voiture->setModifierPar($username); 
            $voiture->setModifierLe($date);
            $voitureRepository->add($voiture);
            return $this->redirectToRoute('Voiture');
        }

        return $this->renderForm('voiture/ModifierVoiture.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    #[Route('/SupprimerVoiture/{id}', name: 'SupprimerVoiture')]
    public function SupprimerVoiture(Voiture $voiture, VoitureRepository $voitureRepository,EntityManagerInterface $manager): Response
    {
        $voiture->setEnable(False);
        $manager->persist($voiture);
        $manager->flush();
        return $this->redirectToRoute('Voiture');
    }

    #[Route('/SupprimerDefinitivementLaVoitureN/{id}', name: 'SupprimerVoitureDef')]
    public function SupprimerVoitureDef(Voiture $voiture, VoitureRepository $voitureRepository): Response
    {
            $voitureRepository->remove($voiture);
            return $this->redirectToRoute('Voiture');
    }


}
