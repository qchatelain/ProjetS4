<?php

// src/Controller/PartieController.php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Objet;
use App\Entity\Partie;
use App\Entity\Objectif;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PartieController
 * @package App\Controller
 * @Route("/partie")
 */
class PartieController extends Controller
{
    /**
     * @Route("/nouvelle", name="nouvelle_partie")
     */
    public function nouvellePartie()
    {
        $joueurs = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('Partie/nouvellePartie.html.twig', ['joueurs' => $joueurs]);
    }

    /**
     * @Route("/creation", name="creation_partie")
     */
    public function creationPartie(Request $request) {
        $idAdversaire = $request->request->get('adversaire');
        $joueur = $this->getDoctrine()->getRepository(User::class)->find(1);
        $adversaire = $this->getDoctrine()->getRepository(User::class)->find($idAdversaire);

        //récupérer les objectifs depuis la base de données
        $objectifs = $this->getDoctrine()->getRepository(Objectif::class)->findAll();
        $tObjectifs = array();

        //Affecter une valeur nulle par défaut à chaque objectif
        foreach ($objectifs as $objectif) {
            $tObjectifs[$objectif->getId()] = 0;
        }

        //récupérer les cartes depuis la base de données
        $cartes = $this->getDoctrine()->getRepository(Objet::class)->findAll();
        $tCartes = array();

        //Affecter des valeurs pour les différentes propriétés de partie
        //mélanger les cartes
        foreach ($cartes as $carte) {
            $tCartes[] = $carte->getId();
        }
        shuffle($tCartes); //mélange le tableau contenant les id

        //retrait de la première carte
        $carteecarte = array_pop($tCartes);

        //Distribution des cartes aux joueurs
        $tMainJ1 = array();
        for($i=0; $i<6; $i++) {
            $tMainJ1[] = array_pop($tCartes);
        }
        $tMainJ2 = array();
        for($i=0; $i<6; $i++) {
            $tMainJ2[] = array_pop($tCartes);
        }

        //La création de la pioche
        $tPioche = $tCartes;

        //Création des actions
        $tActions=array("secret" => 1, "dissimulation" => 1, "cadeau" => 1, "concurrence" => 1);

        //Choix aléatoire du 1er tour
        $tJoueurs = array($joueur->getId(),$adversaire->getId());
        $rand_keys = array_rand($tJoueurs, 1);
        $tour = $tJoueurs[$rand_keys];

        //Récupération de la date
        $datetime = new \Datetime("now");
        $datetime->modify('+ 1 hour');

        //Créer un objet de type Partie
        $partie = new Partie();
        $partie->setPartieDate($datetime);
        $partie->setPartieJ1($joueur);
        $partie->setPartieJ2($adversaire);
        $partie->setPartieCarteEcart($carteecarte);
        $partie->setPartieMainJ1($tMainJ1);
        $partie->setPartieMainJ2($tMainJ2);
        $partie->setPartiePioche($tPioche);
        $partie->setPartieTour($tour);
        $partie->setPartieObjectifs($tObjectifs);
        $partie->setPartieTerrainJ1(array());
        $partie->setPartieTerrainJ2(array());
        $partie->setPartieActionsJ1($tActions);
        $partie->setPartieActionsJ2($tActions);

        //Récupérer le manager de doctrine
        $em = $this->getDoctrine()->getManager();
        //Sauvegarde mon objet Partie dans la base de données
        $em->persist($partie);
        $em->flush();

        return $this->redirectToRoute('afficher_partie', ['id' => $partie->getId()]);
    }

    /**
     * @Route("/afficher/{id}", name="afficher_partie")
     */
    public function afficherPartie(Partie $partie) {

        $tPartie = $this->getDoctrine()->getRepository(Partie::class)->find($partie);
        $objets = $this->getDoctrine()->getRepository(Objet::class)->findAll();
        $objectifs = $this->getDoctrine()->getRepository(Objectif::class)->findAll();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $tObjets = array();
        foreach ($objets as $objet) {
            $tObjets[$objet->getId()] = $objet;
        }

        $tObjectifs = array();
        foreach ($objectifs as $objectif) {
            $tObjectifs[$objectif->getId()] = $objectif;
        }

        $tUsers = array();
        foreach ($users as $user) {
            $tUsers[$user->getId()] = $user;
        }

        return $this->render('Partie/afficher.html.twig',
            ['partie' => $tPartie,
             'objets' => $tObjets,
             'objectifs' => $tObjectifs,
             'users' => $tUsers
            ]
        );
    }
}

?>