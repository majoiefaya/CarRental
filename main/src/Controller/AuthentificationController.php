<?php

namespace App\Controller;

use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AuthentificationController extends AbstractController
{
    #[Route('/Authentification', name: 'Authentification')]
    public function Authentification(Request $request,PersonneRepository $personneRepository,RequestStack $requestStack): Response
    {
        $username=$request->request->get('username');
        $password=$request->request->get('password');

        if ($personne=$personneRepository->findOneBy(['UserName'=>$username,'MotDePasse'=>$password])){
            if($personne!=null && $personne->getId()>0){
                if($personne->getNomRole()=="Client"){
                    $session= $requestStack->getSession();
                    $session->set('nom',$personne->getNom());
                    $session->set('prenom',$personne->getPrenom());
                    $session->set('username',$personne->getUserName());
                    return $this->redirectToRoute('Client');
                }
                else if($personne->getNomRole()=="Gerant"){
                    $session= $requestStack->getSession();
                    $session->set('nom',$personne->getNom());
                    $session->set('prenom',$personne->getPrenom());
                    $session->set('username',$personne->getUserName());
                    return $this->redirectToRoute('Gerant');
                }
               else if($personne->getNomRole()=="Responsable"){
                $session= $requestStack->getSession();
                $session->set('nom',$personne->getNom());
                $session->set('prenom',$personne->getPrenom());
                $session->set('username',$personne->getUserName());
                return $this->redirectToRoute('Acceuil');
               }
            
            }
        }
        return $this->render('Authentification/index.html.twig', [
            'controller_name' => 'AuthentificationController',
        ]);
    }
    
}
