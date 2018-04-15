<?php

// src/Controller/PartieController.php
namespace App\Controller;

use App\Entity\Chat;
use App\Entity\User;
use App\Entity\Objet;
use App\Entity\Partie;
use App\Entity\Objectif;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Doctrine\Common\Collections\Criteria;

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
        $joueurs = $this->getDoctrine()->getRepository(User::class)->findNotEqualId($this->getUser()->getId());

        return $this->render('Partie/nouvellePartie.html.twig', ['joueurs' => $joueurs]);
    }

    /**
     * @Route("/creation", name="creation_partie")
     */
    public function creationPartie(Request $request) {
        $idAdversaire = $request->request->get('adversaire');

        if ($idAdversaire != null) {
            $joueur = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
            $adversaire = $this->getDoctrine()->getRepository(User::class)->find($idAdversaire);

            //récupérer les objectifs depuis la base de données
            $objectifs = $this->getDoctrine()->getRepository(Objectif::class)->findAll();
            $tObjectifs = array();

            //Affecter une valeur nulle par défaut à chaque objectif
            foreach ($objectifs as $objectif) {
                $tObjectifs[$objectif->getId()] = array('etat' => 0, 'point1' => 0, 'point2' => 0);
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
            $tActions=array("secret" => array("etat" => 0, "image" => 'Jetons6.png', "image2" => 'Jetons2.png', "carte" => 0), "dissimulation" => array("etat" => 0, "image" => 'Jetons7.png', "image2" => 'Jetons3.png', "carte" => array()), "cadeau" => array("etat" => 0, "image" => 'Jetons8.png', "image2" => 'Jetons4.png', "carte" => array()), "concurrence" => array("etat" => 0, "image" => 'Jetons5.png', "image2" => 'Jetons1.png', "carte" => array()));

            //Choix aléatoire du 1er tour
            $tJoueurs = array($joueur->getId(),$adversaire->getId());
            $rand_keys = array_rand($tJoueurs, 1);
            $tour = $tJoueurs[$rand_keys];

            // Première carte piochée par le joueur actif
            $carte_piochee = array_pop($tPioche);
            if($joueur->getId() == $tour){
                if($carte_piochee != null){
                    $tMainJ1[] = $carte_piochee;
                }
            }else{
                if($carte_piochee != null){
                    $tMainJ2[] = $carte_piochee;
                }
            }

            //Récupération de la date
            $datetime = new \Datetime("now");
            $datetime->modify('+ 2 hour');

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
        } else {
            return $this->redirectToRoute('nouvelle_partie');
        }
    }

    /**
     * @Route("/afficher/{id}", name="afficher_partie")
     */
    public function afficherPartie(Request $request, Partie $partie) {

        $tPartie = $this->getDoctrine()->getRepository(Partie::class)->find($partie);
        $objets = $this->getDoctrine()->getRepository(Objet::class)->findAll();
        $objectifs = $this->getDoctrine()->getRepository(Objectif::class)->findAll();

        $tObjets = array();
        foreach ($objets as $objet) {
            $tObjets[$objet->getId()] = $objet;
        }

        $tObjectifs = array();
        foreach ($objectifs as $objectif) {
            $tObjectifs[$objectif->getId()] = $objectif;
        }

        if ($this->getUser()->getId() != $tPartie->getPartieJ1()->getId() && $this->getUser()->getId() != $tPartie->getPartieJ2()->getId()) {
            return $this->redirectToRoute('profil');
        }

        $connecte = $this->getUser();

        if($tPartie->getPartieJ1()->getId() == $connecte->getId()) {
            $maMain = $tPartie->getPartieMainJ1();
            $mesActions = $tPartie->getPartieActionsJ1();
            $actionsAdv = $tPartie->getPartieActionsJ2();
            $mesPoints = $tPartie->getPartiePointsJ1();
            $mesObjs = $tPartie->getPartieNbObjJ1();
            $pointsAdv = $tPartie->getPartiePointsJ2();
            $objsAdv = $tPartie->getPartieNbObjJ2();
        } elseif ($tPartie->getPartieJ2()->getId() == $connecte->getId()) {
            $maMain = $tPartie->getPartieMainJ2();
            $mesActions = $tPartie->getPartieActionsJ2();
            $actionsAdv = $tPartie->getPartieActionsJ1();
            $mesPoints = $tPartie->getPartiePointsJ2();
            $mesObjs = $tPartie->getPartieNbObjJ2();
            $pointsAdv = $tPartie->getPartiePointsJ1();
            $objsAdv = $tPartie->getPartieNbObjJ1();
        }


        //afficher actions du joueur à qui c'est le tour
        $tour = $tPartie->getPartieTour();
        $tActionsDispo = array();
        if($tour == $connecte->getId()){
            foreach ($mesActions as $key=>$value){
                foreach ($value as $key2=>$value2){
                    if($key2=='etat' && $value2==0){
                        $tActionsDispo[] = ['nom'=>$key, 'etat'=>0 ,'dispo'=>1];
                    }elseif($key2=='etat' && $value2==1){
                        $tActionsDispo[] = ['nom'=>$key, 'etat'=>1 ,'dispo'=>0];
                    }
                }
            }
        }else{
            foreach ($mesActions as $key=>$value){
                foreach ($value as $key2=>$value2){
                    if($key2=='etat' && $value2==0){
                        $tActionsDispo[] = ['nom'=>$key, 'etat'=>0 ,'dispo'=>0];
                    }elseif($key2=='etat' && $value2==1){
                        $tActionsDispo[] = ['nom'=>$key, 'etat'=>1 ,'dispo'=>0];
                    }
                }
            }
        }

        //récupération de l'action sélectionnée et modification de son état
        $action_selectionnee = $request->request->get('action');
        if($action_selectionnee != null){
            switch ($action_selectionnee) {
                case 'secret':
                    break;
                case 'dissimulation':
                    break;
                case 'cadeau':
                    break;
                case 'concurrence':
                    break;
            }
            //enregistrer
            if($tPartie->getPartieJ1()->getId() == $connecte->getId()){
                $tPartie->setPartieActionsJ1($mesActions);
            }else{
                $tPartie->setPartieActionsJ2($mesActions);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($tPartie);
            $em->flush();
        }

        //Enregiste la carte secrète
        $carte_secret_select = $request->request->get('carte_sec_1');
        if($carte_secret_select != null){
            $nMain= array();
            if($tPartie->getPartieJ1()->getId() == $connecte->getId()){
                $mesActions = $tPartie->getPartieActionsJ1();
                $mesActions['secret']['carte'] = $carte_secret_select;
                $mesActions['secret']['etat'] = 1;
                $tPartie->setPartieActionsJ1($mesActions);
                $maMain = $tPartie->getPartieMainJ1();
                foreach($maMain as $key=>$value){
                    if($value == $carte_secret_select){
                        unset($maMain[$key]);
                    }else{
                        $nMain[]=$value;
                    }
                }
                $tPartie->setPartieMainJ1($nMain);
            }else{
                $mesActions = $tPartie->getPartieActionsJ2();
                $mesActions['secret']['carte'] = $carte_secret_select;
                $mesActions['secret']['etat'] = 1;
                $tPartie->setPartieActionsJ2($mesActions);
                $maMain = $tPartie->getPartieMainJ2();
                foreach($maMain as $key=>$value){
                    if($value == $carte_secret_select){
                        unset($maMain[$key]);
                    }else{
                        $nMain[]=$value;
                    }
                }
                $tPartie->setPartieMainJ2($nMain);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($tPartie);
            $em->flush();

            //changer tour + piocher carte
            $tour = $tPartie->getPartieTour();
            $pioche = $tPartie->getPartiePioche();
            $carte_piochee = array_pop($pioche);
            if($tPartie->getPartieJ1()->getId() == $tour){
                $nMain = $tPartie->getPartieMainJ2();
                if($carte_piochee != null){
                    $nMain[] = $carte_piochee;
                }
                $tPartie->setPartieTour($tPartie->getPartieJ2()->getId());
                $tPartie->setPartiePioche($pioche);
                $tPartie->setPartieMainJ2($nMain);
            }else{
                $nMain = $tPartie->getPartieMainJ1();
                if($carte_piochee != null){
                    $nMain[] = $carte_piochee;
                }
                $tPartie->setPartieTour($tPartie->getPartieJ1()->getId());
                $tPartie->setPartiePioche($pioche);
                $tPartie->setPartieMainJ1($nMain);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($tPartie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $tPartie->getId(), 'partie'=>$tPartie]);
        }

        //Enregistrer les cartes dissimulées
        $carte1_diss = $request->request->get('carte_diss_1');
        $carte2_diss = $request->request->get('carte_diss_2');
        $cartes_diss_select = array();
        $cartes_diss_select[] = $carte1_diss;
        $cartes_diss_select[] = $carte2_diss;
        if($cartes_diss_select[0] != null and $cartes_diss_select[1] != null){
            $nMain= array();
            if($tPartie->getPartieJ1()->getId() == $connecte->getId()){
                $mesActions = $tPartie->getPartieActionsJ1();
                $mesActions['dissimulation']['carte'] = $cartes_diss_select;
                $mesActions['dissimulation']['etat'] = 1;
                $tPartie->setPartieActionsJ1($mesActions);
                $maMain = $tPartie->getPartieMainJ1();
                foreach($maMain as $key=>$value){
                    if($value == $cartes_diss_select[0]){
                        unset($maMain[$key]);
                    }elseif($value == $cartes_diss_select[1]){
                        unset($maMain[$key]);
                    }else{
                        $nMain[]= $value;
                    }
                }
                $tPartie->setPartieMainJ1($nMain);
            }else{
                $mesActions = $tPartie->getPartieActionsJ2();
                $mesActions['dissimulation']['carte'] = $cartes_diss_select;
                $mesActions['dissimulation']['etat'] = 1;
                $tPartie->setPartieActionsJ2($mesActions);
                $maMain = $tPartie->getPartieMainJ2();
                foreach($maMain as $key=>$value){
                    if($value == $cartes_diss_select[0]){
                        unset($maMain[$key]);
                    }elseif($value == $cartes_diss_select[1]){
                        unset($maMain[$key]);
                    }else{
                        $nMain[]= $value;
                    }
                }
                $tPartie->setPartieMainJ2($nMain);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($tPartie);
            $em->flush();

            //changer tour + piocher carte
            $tour = $tPartie->getPartieTour();
            $pioche = $tPartie->getPartiePioche();
            $carte_piochee = array_pop($pioche);
            if($tPartie->getPartieJ1()->getId() == $tour){
                $nMain = $tPartie->getPartieMainJ2();
                if($carte_piochee != null){
                    $nMain[] = $carte_piochee;
                }
                $tPartie->setPartieTour($tPartie->getPartieJ2()->getId());
                $tPartie->setPartiePioche($pioche);
                $tPartie->setPartieMainJ2($nMain);
            }else{
                $nMain = $tPartie->getPartieMainJ1();
                if($carte_piochee != null){
                    $nMain[] = $carte_piochee;
                }
                $tPartie->setPartieTour($tPartie->getPartieJ1()->getId());
                $tPartie->setPartiePioche($pioche);
                $tPartie->setPartieMainJ1($nMain);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($tPartie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $tPartie->getId(), 'partie'=>$tPartie]);
        }

        //Enregistrer les cartes cadeau
        $carte1_cadeau = $request->request->get('carte_cadeau_1');
        $carte2_cadeau = $request->request->get('carte_cadeau_2');
        $carte3_cadeau = $request->request->get('carte_cadeau_3');
        $cartes_cadeau_select = array();
        $cartes_cadeau_select[] = $carte1_cadeau;
        $cartes_cadeau_select[] = $carte2_cadeau;
        $cartes_cadeau_select[] = $carte3_cadeau;
        if($cartes_cadeau_select[0] != null && $cartes_cadeau_select[1] != null && $cartes_cadeau_select[2] != null){
            $nMain = array();
            if($tPartie->getPartieJ1()->getId() == $connecte->getId()){
                $mesActions = $tPartie->getPartieActionsJ1();
                $mesActions['cadeau']['carte'] = $cartes_cadeau_select;
                $mesActions['cadeau']['etat'] = 1;
                $tPartie->setPartieActionsJ1($mesActions);
                $maMain = $tPartie->getPartieMainJ1();
                foreach($maMain as $key=>$value){
                    if($value == $cartes_cadeau_select[0]){
                        unset($maMain[$key]);
                    }elseif($value == $cartes_cadeau_select[1]){
                        unset($maMain[$key]);
                    }elseif($value == $cartes_cadeau_select[2]){
                        unset($maMain[$key]);
                    }else{
                        $nMain[]= $value;
                    }
                }
                $tPartie->setPartieMainJ1($nMain);
            }else{
                $mesActions = $tPartie->getPartieActionsJ2();
                $mesActions['cadeau']['carte'] = $cartes_cadeau_select;
                $mesActions['cadeau']['etat'] = 1;
                $tPartie->setPartieActionsJ2($mesActions);
                $maMain = $tPartie->getPartieMainJ2();
                foreach($maMain as $key=>$value){
                    if($value == $cartes_cadeau_select[0]){
                        unset($maMain[$key]);
                    }elseif($value == $cartes_cadeau_select[1]){
                        unset($maMain[$key]);
                    }elseif($value == $cartes_cadeau_select[2]){
                        unset($maMain[$key]);
                    }else{
                        $nMain[]= $value;
                    }
                }
                $tPartie->setPartieMainJ2($nMain);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($tPartie);
            $em->flush();

            return $this->redirectToRoute('afficher_partie', ['id' => $tPartie->getId(), 'partie'=>$tPartie]);
        }

        //Placer la carte cadeau choisie
        $carte_cadeau_select = $request->request->get('carte_cadeau_choisie');
        if($carte_cadeau_select != null){
            $pObjectifs = $tPartie->getPartieObjectifs();
            if($tPartie->getPartieJ1()->getId() == $connecte->getId()){
                $nTerrain = $tPartie->getPartieTerrainJ1();
                $nTerrain[] = $carte_cadeau_select;
                $carte = $this->getDoctrine()->getRepository(Objet::class)->find($carte_cadeau_select);
                $obj_carte = $carte->getObjectifId()->getId();
                $pObjectifs[$obj_carte]['point1'] += 1;
                $tPartie->setPartieTerrainJ1($nTerrain);
                $tPartie->setPartieObjectifs($pObjectifs);
            }else{
                $nTerrain = $tPartie->getPartieTerrainJ2();
                $nTerrain[] = $carte_cadeau_select;
                $carte = $this->getDoctrine()->getRepository(Objet::class)->find($carte_cadeau_select);
                $obj_carte = $carte->getObjectifId()->getId();
                $pObjectifs[$obj_carte]['point2'] += 1;
                $tPartie->setPartieTerrainJ2($nTerrain);
                $tPartie->setPartieObjectifs($pObjectifs);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($tPartie);
            $em->flush();

            //Enlever l'action adverse et ses cartes cadeaux
            if($tPartie->getPartieJ1()->getId() == $connecte->getId()){
                $actionsAdv = $tPartie->getPartieActionsJ2();
            }else{
                $actionsAdv = $tPartie->getPartieActionsJ1();
            }

            $cartes_cadeaux = $actionsAdv['cadeau']['carte'];
            $cadeaux_restant = array();
            if($cartes_cadeaux != null ) {
                if ($carte_cadeau_select == $cartes_cadeaux[0]) {
                    unset($actionsAdv['cadeau']['carte'][0]);
                } else {
                    $cadeaux_restant [] = $cartes_cadeaux[0];
                    unset($actionsAdv['cadeau']['carte'][0]);
                }
                if ($carte_cadeau_select == $cartes_cadeaux[1]) {
                    unset($actionsAdv['cadeau']['carte'][1]);
                } else {
                    $cadeaux_restant [] = $cartes_cadeaux[1];
                    unset($actionsAdv['cadeau']['carte'][1]);
                }
                if ($carte_cadeau_select == $cartes_cadeaux[2]) {
                    unset($actionsAdv['cadeau']['carte'][2]);
                } else {
                    $cadeaux_restant [] = $cartes_cadeaux[2];
                    unset($actionsAdv['cadeau']['carte'][2]);
                }

                //Attribution des cadeaux restants à l'adversaire
                if($tPartie->getPartieJ1()->getId() == $connecte->getId()){
                    $nTerrain = $tPartie->getPartieTerrainJ2();
                    foreach ($cadeaux_restant as $id) {
                        $nTerrain[] = $id;
                        $carte = $this->getDoctrine()->getRepository(Objet::class)->find($id);
                        $obj_carte = $carte->getObjectifId()->getId();
                        $pObjectifs[$obj_carte]['point2'] += 1;
                    }
                    $tPartie->setPartieTerrainJ2($nTerrain);
                    $tPartie->setPartieObjectifs($pObjectifs);
                } else{
                    $nTerrain = $tPartie->getPartieTerrainJ1();
                    foreach ($cadeaux_restant as $id) {
                        $nTerrain[] = $id;
                        $carte = $this->getDoctrine()->getRepository(Objet::class)->find($id);
                        $obj_carte = $carte->getObjectifId()->getId();
                        $pObjectifs[$obj_carte]['point1'] += 1;
                    }
                    $tPartie->setPartieTerrainJ1($nTerrain);
                    $tPartie->setPartieObjectifs($pObjectifs);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($tPartie);
                $em->flush();

                //Enregistrer les changements d'action adverse liés à la carte cadeau choisie
                if($tPartie->getPartieJ1()->getId() == $connecte->getId()){
                    $tPartie->setPartieActionsJ2($actionsAdv);
                }else{
                    $tPartie->setPartieActionsJ1($actionsAdv);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($tPartie);
                $em->flush();
            }

            //changer tour + piocher carte
            $tour = $tPartie->getPartieTour();
            $pioche = $tPartie->getPartiePioche();
            $carte_piochee = array_pop($pioche);
            if($tPartie->getPartieJ2()->getId() == $tour){
                $nMain = $tPartie->getPartieMainJ1();
                if($carte_piochee != null){
                    $nMain[] = $carte_piochee;
                }
                $tPartie->setPartieTour($tPartie->getPartieJ1()->getId());
                $tPartie->setPartiePioche($pioche);
                $tPartie->setPartieMainJ1($nMain);
            }else{
                $nMain = $tPartie->getPartieMainJ2();
                if($carte_piochee != null){
                    $nMain[] = $carte_piochee;
                }
                $tPartie->setPartieTour($tPartie->getPartieJ2()->getId());
                $tPartie->setPartiePioche($pioche);
                $tPartie->setPartieMainJ2($nMain);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($tPartie);
            $em->flush();

            return $this->redirectToRoute('afficher_partie', ['id' => $tPartie->getId(), 'partie'=>$tPartie]);
        }

        //Afficher cartes cadeau proposées par l'adversaire si elles existent
        if($actionsAdv['cadeau']['carte'] != null){
            $choix_cadeau = $actionsAdv['cadeau']['carte'];
            $tChoix_cadeau = array();
            foreach($choix_cadeau as $id) {
                $carte = $this->getDoctrine()->getRepository(Objet::class)->find($id);
                $image = $carte->getObjetImg();
                $tChoix_cadeau [] = ['id'=>$id, 'image'=>$image];
            }
        } else {
            $tChoix_cadeau = 0;
        }

        //Récupération des paires concurrence choisies et les enregistrer
        $carte1_concurrence = $request->request->get('carte_conc_1');
        $carte2_concurrence = $request->request->get('carte_conc_2');
        $carte3_concurrence = $request->request->get('carte_conc_3');
        $carte4_concurrence = $request->request->get('carte_conc_4');
        $paire1_concurrence = array();
        $paire1_concurrence[] = $carte1_concurrence;
        $paire1_concurrence[] = $carte2_concurrence;
        $paire2_concurrence = array();
        $paire2_concurrence[] = $carte3_concurrence;
        $paire2_concurrence[] = $carte4_concurrence;
        $paires_concurrence = array("p1" => $paire2_concurrence, "p2" => $paire1_concurrence);
        if($paires_concurrence['p1'][0] != null && $paires_concurrence['p2'][0] != null){
            $nMain= array();
            if($tPartie->getPartieJ1()->getId() == $connecte->getId()){
                $mesActions = $tPartie->getPartieActionsJ1();
                $mesActions['concurrence']['carte'] = $paires_concurrence;
                $mesActions['concurrence']['etat'] = 1;
                $tPartie->setPartieActionsJ1($mesActions);
                $maMain = $tPartie->getPartieMainJ1();
                foreach($maMain as $key=>$value){
                    if($value == $paires_concurrence['p1'][0]){
                        unset($maMain[$key]);
                    }elseif($value == $paires_concurrence['p1'][1]){
                        unset($maMain[$key]);
                    }elseif($value == $paires_concurrence['p2'][0]){
                        unset($maMain[$key]);
                    }elseif($value == $paires_concurrence['p2'][1]){
                        unset($maMain[$key]);
                    }else{
                        $nMain[]= $value;
                    }
                }
                $tPartie->setPartieMainJ1($nMain);
            }else{
                $mesActions = $tPartie->getPartieActionsJ2();
                $mesActions['concurrence']['carte'] = $paires_concurrence;
                $mesActions['concurrence']['etat'] = 1;
                $tPartie->setPartieActionsJ2($mesActions);
                $maMain = $tPartie->getPartieMainJ2();
                foreach($maMain as $key=>$value){
                    if($value == $paires_concurrence['p1'][0]){
                        unset($maMain[$key]);
                    }elseif($value == $paires_concurrence['p1'][1]){
                        unset($maMain[$key]);
                    }elseif($value == $paires_concurrence['p2'][0]){
                        unset($maMain[$key]);
                    }elseif($value == $paires_concurrence['p2'][1]){
                        unset($maMain[$key]);
                    }else{
                        $nMain[]= $value;
                    }
                }
                $tPartie->setPartieMainJ2($nMain);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($tPartie);
            $em->flush();

            return $this->redirectToRoute('afficher_partie', ['id' => $tPartie->getId(), 'partie'=>$tPartie]);
        }

        //Recupération de la paire choisie et attribuer les objectifs uniquement pour cette paire
        $paire_concurrence_select = $request->request->get('choix_paire');
        if($paire_concurrence_select != null){

            //Enlever l'action adverse et la paire
            if($tPartie->getPartieJ1()->getId() == $connecte->getId()){
                $mesActions = $tPartie->getPartieActionsJ2();
            }else{
                $mesActions = $tPartie->getPartieActionsJ1();
            }

            $maPaire = "";
            $paireAdv = "";
            if($paire_concurrence_select == 'p1' && $mesActions['concurrence']['carte'] != null){
                $maPaire= $mesActions['concurrence']['carte']['p1'];
                $paireAdv = $mesActions['concurrence']['carte']['p2'];
            } elseif($paire_concurrence_select == 'p2' && $mesActions['concurrence']['carte'] != null){
                $maPaire= $mesActions['concurrence']['carte']['p2'];
                $paireAdv = $mesActions['concurrence']['carte']['p1'];
            }

            //Placer les cartes sur le terrain
            if($maPaire != null && $paireAdv !=null){
                $pObjectifs = $tPartie->getPartieObjectifs();
                $nTerrain1 = $tPartie->getPartieTerrainJ1();
                $nTerrain2 = $tPartie->getPartieTerrainJ2();
                if($tPartie->getPartieJ1()->getId() == $connecte->getId()){
                    foreach ($maPaire as $id) {
                        $nTerrain1[] = $id;
                        $carte = $this->getDoctrine()->getRepository(Objet::class)->find($id);
                        $obj_carte = $carte->getObjectifId()->getId();
                        $pObjectifs[$obj_carte]['point1'] += 1;
                    }
                    foreach ($paireAdv as $id) {
                        $nTerrain2[] = $id;
                        $carte = $this->getDoctrine()->getRepository(Objet::class)->find($id);
                        $obj_carte = $carte->getObjectifId()->getId();
                        $pObjectifs[$obj_carte]['point2'] += 1;
                    }
                } else {
                    foreach ($maPaire as $id) {
                        $nTerrain2[] = $id;
                        $carte = $this->getDoctrine()->getRepository(Objet::class)->find($id);
                        $obj_carte = $carte->getObjectifId()->getId();
                        $pObjectifs[$obj_carte]['point2'] += 1;
                    }
                    foreach ($paireAdv as $id) {
                        $nTerrain1[] = $id;
                        $carte = $this->getDoctrine()->getRepository(Objet::class)->find($id);
                        $obj_carte = $carte->getObjectifId()->getId();
                        $pObjectifs[$obj_carte]['point1'] += 1;
                    }
                }
                $tPartie->setPartieTerrainJ1($nTerrain1);
                $tPartie->setPartieTerrainJ2($nTerrain2);
                $tPartie->setPartieObjectifs($pObjectifs);
                unset($mesActions['concurrence']['carte']['p1']);
                unset($mesActions['concurrence']['carte']['p2']);

                //Enregistrer les changements d'action adverse liés à la carte cadeau choisie
                if($tPartie->getPartieJ1()->getId() == $connecte->getId()){
                    $tPartie->setPartieActionsJ2($mesActions);
                }else{
                    $tPartie->setPartieActionsJ1($mesActions);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($tPartie);
                $em->flush();
            }

            //changer tour + piocher carte
            $tour = $tPartie->getPartieTour();
            $pioche = $tPartie->getPartiePioche();
            $carte_piochee = array_pop($pioche);
            if($tPartie->getPartieJ2()->getId() == $tour){
                $nMain = $tPartie->getPartieMainJ1();
                if($carte_piochee != null){
                    $nMain[] = $carte_piochee;
                }
                $tPartie->setPartieTour($tPartie->getPartieJ1()->getId());
                $tPartie->setPartiePioche($pioche);
                $tPartie->setPartieMainJ1($nMain);
            }else{
                $nMain = $tPartie->getPartieMainJ2();
                if($carte_piochee != null){
                    $nMain[] = $carte_piochee;
                }
                $tPartie->setPartieTour($tPartie->getPartieJ2()->getId());
                $tPartie->setPartiePioche($pioche);
                $tPartie->setPartieMainJ2($nMain);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($tPartie);
            $em->flush();

            return $this->redirectToRoute('afficher_partie', ['id' => $tPartie->getId(), 'partie'=>$tPartie]);
        }

        //Afficher les paires concurrence proposées par l'adversaire si elles existent
        if($actionsAdv['concurrence']['carte'] != null){
            $choix_paire = $actionsAdv['concurrence']['carte'];
            $tChoix_paire = array();
            foreach($choix_paire as $key=>$value){
                foreach($value as $id){
                    $carte = $this->getDoctrine()->getRepository(Objet::class)->find($id);
                    $image = $carte->getObjetImg();
                    if($key=='p1'){
                        $tChoix_paire[]=['id'=>$id, 'image'=>$image, 'paire'=>1];
                    } elseif ($key=='p2'){
                        $tChoix_paire[]=['id'=>$id, 'image'=>$image, 'paire'=>2];
                    }
                }
            }
        } else {
            $tChoix_paire = 0;
        }

        if($mesActions['cadeau']['carte'] != null){
            $tour_cadeau = 1;
            $att_cadeau = 0;
        } else {
            $tour_cadeau = 0;
            $att_cadeau = 1;
        }
        if($mesActions['concurrence']['carte'] != null){
            $tour_paire = 1;
            $att_paire = 0;
        } else {
            $tour_paire = 0;
            $att_paire = 1;
        }

        //Vérifier l'état de toutes les actions
        $main1 = $tPartie->getPartieMainJ1();
        $main2 = $tPartie->getPartieMainJ2();
        $action1 = $tPartie->getPartieActionsJ1();
        $action2 = $tPartie->getPartieActionsJ2();

        if( $main1 == null && $main2 == null && $action1['secret']['etat'] == 1 && $action2['secret']['etat'] == 1 && $action1['cadeau']['carte'] == null && $action2['cadeau']['carte'] == null && $action1['concurrence']['carte'] == null && $action2['concurrence']['carte'] == null ){
            //placer les cartes secrètes sur le terrain
            $pObjectifs = $tPartie->getPartieObjectifs();

            if ($action1['secret']['carte'] != null && $action2['secret']['carte'] != null) {
                $cartej1 = $action1['secret']['carte'];
                $cartej2 = $action2['secret']['carte'];

                $nTerrain1 = $tPartie->getPartieTerrainJ1();
                $nTerrain1[] = $cartej1;
                $carte = $this->getDoctrine()->getRepository(Objet::class)->find($cartej1);
                $obj_carte = $carte->getObjectifId()->getId();
                $pObjectifs[$obj_carte]['point1'] += 1;
                $tPartie->setPartieTerrainJ1($nTerrain1);

                $nTerrain2 = $tPartie->getPartieTerrainJ2();
                $nTerrain2[] = $cartej2;
                $carte = $this->getDoctrine()->getRepository(Objet::class)->find($cartej2);
                $obj_carte = $carte->getObjectifId()->getId();
                $pObjectifs[$obj_carte]['point2'] += 1;
                $tPartie->setPartieTerrainJ2($nTerrain2);

                $tPartie->setPartieObjectifs($pObjectifs);

                //calcul des scores
                $terrainj1 = $tPartie->getPartieTerrainJ1();
                $terrainj2 = $tPartie->getPartieTerrainJ2();
                $tObj1J1 = array();
                $tObj2J1 = array();
                $tObj3J1 = array();
                $tObj4J1 = array();
                $tObj5J1 = array();
                $tObj6J1 = array();
                $tObj7J1 = array();
                $tObj1J2 = array();
                $tObj2J2 = array();
                $tObj3J2 = array();
                $tObj4J2 = array();
                $tObj5J2 = array();
                $tObj6J2 = array();
                $tObj7J2 = array();
                $scoreJ1 = 0;
                $scoreJ2 = 0;
                $nbObjJ1 = 0;
                $nbObjJ2 = 0;
                if ($terrainj1 != null && $terrainj2 != null) {
                    foreach ($terrainj1 as $id) {
                        $objTerrain = $this->getDoctrine()->getRepository(Objet::class)->find($id);
                        $objet_id = $objTerrain->getObjectifId()->getId();
                        if ($objet_id == 1) {
                            $tObj1J1[] = $id;
                        } elseif ($objet_id == 2) {
                            $tObj2J1[] = $id;
                        } elseif ($objet_id == 3) {
                            $tObj3J1[] = $id;
                        } elseif ($objet_id == 4) {
                            $tObj4J1[] = $id;
                        } elseif ($objet_id == 5) {
                            $tObj5J1[] = $id;
                        } elseif ($objet_id == 6) {
                            $tObj6J1[] = $id;
                        } elseif ($objet_id == 7) {
                            $tObj7J1[] = $id;
                        }
                    }
                    foreach ($terrainj2 as $id) {
                        $objTerrain = $this->getDoctrine()->getRepository(Objet::class)->find($id);
                        $objet_id = $objTerrain->getObjectifId()->getId();
                        if ($objet_id == 1) {
                            $tObj1J2[] = $id;
                        } elseif ($objet_id == 2) {
                            $tObj2J2[] = $id;
                        } elseif ($objet_id == 3) {
                            $tObj3J2[] = $id;
                        } elseif ($objet_id == 4) {
                            $tObj4J2[] = $id;
                        } elseif ($objet_id == 5) {
                            $tObj5J2[] = $id;
                        } elseif ($objet_id == 6) {
                            $tObj6J2[] = $id;
                        } elseif ($objet_id == 7) {
                            $tObj7J2[] = $id;
                        }
                    }
                    if (count($tObj1J1) > count($tObj1J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[1]['etat'] = $tPartie->getPartieJ1()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ1 += 2;
                        $nbObjJ1 += 1;
                    } elseif (count($tObj1J1) < count($tObj1J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[1]['etat'] = $tPartie->getPartieJ2()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ2 += 2;
                        $nbObjJ2 += 1;
                    }
                    if (count($tObj2J1) > count($tObj2J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[2]['etat'] = $tPartie->getPartieJ1()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ1 += 2;
                        $nbObjJ1 += 1;
                    } elseif (count($tObj2J1) < count($tObj2J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[2]['etat'] = $tPartie->getPartieJ2()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ2 += 2;
                        $nbObjJ2 += 1;
                    }
                    if (count($tObj3J1) > count($tObj3J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[3]['etat'] = $tPartie->getPartieJ1()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ1 += 2;
                        $nbObjJ1 += 1;
                    } elseif (count($tObj3J1) < count($tObj3J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[3]['etat'] = $tPartie->getPartieJ2()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ2 += 2;
                        $nbObjJ2 += 1;
                    }
                    if (count($tObj4J1) > count($tObj4J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[4]['etat'] = $tPartie->getPartieJ1()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ1 += 3;
                        $nbObjJ1 += 1;
                    } elseif (count($tObj4J1) < count($tObj4J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[4]['etat'] = $tPartie->getPartieJ2()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ2 += 3;
                        $nbObjJ2 += 1;
                    }
                    if (count($tObj5J1) > count($tObj5J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[5]['etat'] = $tPartie->getPartieJ1()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ1 += 3;
                        $nbObjJ1 += 1;
                    } elseif (count($tObj5J1) < count($tObj5J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[5]['etat'] = $tPartie->getPartieJ2()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ2 += 3;
                        $nbObjJ2 += 1;
                    }
                    if (count($tObj6J1) > count($tObj6J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[6]['etat'] = $tPartie->getPartieJ1()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ1 += 4;
                        $nbObjJ1 += 1;
                    } elseif (count($tObj6J1) < count($tObj6J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[6]['etat'] = $tPartie->getPartieJ2()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ2 += 4;
                        $nbObjJ2 += 1;
                    }
                    if (count($tObj7J1) > count($tObj7J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[7]['etat'] = $tPartie->getPartieJ1()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ1 += 5;
                        $nbObjJ1 += 1;
                    } elseif (count($tObj7J1) < count($tObj7J2)) {
                        $obj = $tPartie->getPartieObjectifs();
                        $obj[7]['etat'] = $tPartie->getPartieJ2()->getId();
                        $tPartie->setPartieObjectifs($obj);
                        $scoreJ2 += 5;
                        $nbObjJ2 += 1;
                    }
                }
                $tPartie->setPartiePointsJ1($scoreJ1);
                $tPartie->setPartiePointsJ2($scoreJ2);
                $tPartie->setPartieNbObjJ1($nbObjJ1);
                $tPartie->setPartieNbObjJ2($nbObjJ2);

                $action1['secret']['carte'] = 0;
                $action2['secret']['carte'] = 0;
                $tPartie->setPartieActionsJ1($action1);
                $tPartie->setPartieActionsJ2($action2);

                $em = $this->getDoctrine()->getManager();
                $em->persist($tPartie);
                $em->flush();
            }

            //Check les scores
            $scoreJ1 = $tPartie->getPartiePointsJ1();
            $scoreJ2 = $tPartie->getPartiePointsJ1();
            $nbObjJ1 = $tPartie->getPartieNbObjJ1();
            $nbObjJ2 = $tPartie->getPartieNbObjJ2();

            $joueur1 = $this->getDoctrine()->getRepository(User::class)->find($tPartie->getPartieJ1()->getId());
            $joueur2 = $this->getDoctrine()->getRepository(User::class)->find($tPartie->getPartieJ2()->getId());

            if($scoreJ1 > 10){
                $message = $joueur1->getUsername().' est vainqueur';
                $tPartie->setPartieVainqueur($tPartie->getPartieJ1()->getId());
                $victoire = $joueur1->getUserNbVictoire();
                $partie1 = $joueur1->getUserNbVictoire();
                $partie2 = $joueur2->getUserNbVictoire();
                $victoire++;
                $partie1++;
                $partie2++;
                $joueur1->setUserNbVictoire($victoire);
                $joueur1->setUserNbPartie($partie1);
                $joueur2->setUserNbPartie($partie2);
            }elseif($scoreJ2 > 10){
                $message = $joueur2->getUsername().' est vainqueur';
                $tPartie->setPartieVainqueur($tPartie->getPartieJ2()->getId());
                $victoire = $joueur2->getUserNbVictoire();
                $partie1 = $joueur1->getUserNbVictoire();
                $partie2 = $joueur2->getUserNbVictoire();
                $victoire++;
                $partie1++;
                $partie2++;
                $joueur2->setUserNbVictoire($victoire);
                $joueur1->setUserNbPartie($partie1);
                $joueur2->setUserNbPartie($partie2);
            }elseif($scoreJ1 < 11 && $scoreJ2 < 11){
                //Check les objectifs
                if($nbObjJ1 > 3){
                    $message = $joueur1->getUsername().' est vainqueur';
                    $tPartie->setPartieVainqueur($tPartie->getPartieJ1()->getId());
                    $victoire = $joueur1->getUserNbVictoire();
                    $partie1 = $joueur1->getUserNbVictoire();
                    $partie2 = $joueur2->getUserNbVictoire();
                    $victoire++;
                    $partie1++;
                    $partie2++;
                    $joueur1->setUserNbVictoire($victoire);
                    $joueur1->setUserNbPartie($partie1);
                    $joueur2->setUserNbPartie($partie2);
                }elseif($nbObjJ2 > 3){
                    $message = $joueur2->getUsername().' est vainqueur';
                    $tPartie->setPartieVainqueur($tPartie->getPartieJ2()->getId());
                    $victoire = $joueur2->getUserNbVictoire();
                    $partie1 = $joueur1->getUserNbVictoire();
                    $partie2 = $joueur2->getUserNbVictoire();
                    $victoire++;
                    $partie1++;
                    $partie2++;
                    $joueur2->setUserNbVictoire($victoire);
                    $joueur1->setUserNbPartie($partie1);
                    $joueur2->setUserNbPartie($partie2);
                }else{
                    $message = 'Aucun vainqueur';
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($tPartie);
            $em->persist($joueur1);
            $em->persist($joueur2);
            $em->flush();

        }else{
            $message ="";
        }

        //Afficher la carte secrète
        if($tPartie->getPartieJ1()->getId() == $connecte->getId()){
            $mesActions = $tPartie->getPartieActionsJ1();
        } else {
            $mesActions = $tPartie->getPartieActionsJ2();
        }

        if($mesActions['secret']['carte'] != 0){
            $carte_secret = $mesActions['secret']['carte'];
            $objet = $this->getDoctrine()->getRepository(Objet::class)->find($carte_secret);
            $carte_secret = $objet->getId();
        } else {
            $carte_secret = "";
        }

        //Afficher les cartes dissimulées
        if($mesActions['dissimulation']['carte'] != 0){
            $cartes_diss = $mesActions['dissimulation']['carte'];
            $tCartesDiss = array();
            foreach($cartes_diss as $id){
                $objet = $this->getDoctrine()->getRepository(Objet::class)->find($id);
                $tCartesDiss[] = $objet->getId();
            }
        } else {
            $tCartesDiss = "";
        }

        //Passer à la manche suivante
        $manche_suivante = $request->request->get('manche_suivante');
        if($manche_suivante != null){
            //incrémenter la manche
            $manche = $tPartie->getPartieManche();
            $manche ++;
            $tPartie->setPartieManche($manche);
            //reset du plateau
            $cartes = $this->getDoctrine()->getRepository(Objet::class)->findAll();
            $tCartes = array();
            foreach ($cartes as $carte) {
                $tCartes[] = $carte->getId();
            }
            shuffle($tCartes);
            $carteecarte = array_pop($tCartes);
            $tMainJ1 = array();
            for($i=0; $i<6; $i++) {
                $tMainJ1[] = array_pop($tCartes);
            }
            $tMainJ2 = array();
            for($i=0; $i<6; $i++) {
                $tMainJ2[] = array_pop($tCartes);
            }
            $tPioche = $tCartes;
            $tJoueurs = array($tPartie->getPartieJ1()->getId(),$tPartie->getPartieJ2()->getId());
            $rand_keys = array_rand($tJoueurs, 1);
            $tour = $tJoueurs[$rand_keys];
            // Première carte piochée par le joueur actif
            $carte_piochee = array_pop($tPioche);
            if($tPartie->getPartieJ1()->getId() == $tour){
                if($carte_piochee != null){
                    $tMainJ1[] = $carte_piochee;
                }
            }else{
                if($carte_piochee != null){
                    $tMainJ2[] = $carte_piochee;
                }
            }
            $tActions=array("secret" => array("etat" => 0, "image" => 'Jetons6.png', "image2" => 'Jetons2.png', "carte" => 0), "dissimulation" => array("etat" => 0, "image" => 'Jetons7.png', "image2" => 'Jetons3.png', "carte" => array()), "cadeau" => array("etat" => 0, "image" => 'Jetons8.png', "image2" => 'Jetons4.png', "carte" => array()), "concurrence" => array("etat" => 0, "image" => 'Jetons5.png', "image2" => 'Jetons1.png', "carte" => array()));

            //Reinitialise les points des objectifs
            $nObjectifs = $tPartie->getPartieObjectifs();
            $nObjectifs[1]['point1'] = 0;
            $nObjectifs[1]['point2'] = 0;
            $nObjectifs[2]['point1'] = 0;
            $nObjectifs[2]['point2'] = 0;
            $nObjectifs[3]['point1'] = 0;
            $nObjectifs[3]['point2'] = 0;
            $nObjectifs[4]['point1'] = 0;
            $nObjectifs[4]['point2'] = 0;
            $nObjectifs[5]['point1'] = 0;
            $nObjectifs[5]['point2'] = 0;
            $nObjectifs[6]['point1'] = 0;
            $nObjectifs[6]['point2'] = 0;
            $nObjectifs[7]['point1'] = 0;
            $nObjectifs[7]['point2'] = 0;

            $tPartie->setPartieObjectifs($nObjectifs);
            $tPartie->setPartieCarteEcart($carteecarte);
            $tPartie->setPartieMainJ1($tMainJ1);
            $tPartie->setPartieMainJ2($tMainJ2);
            $tPartie->setPartiePioche($tPioche);
            $tPartie->setPartieTour($tour);
            $tPartie->setPartieTerrainJ1(array());
            $tPartie->setPartieTerrainJ2(array());
            $tPartie->setPartieActionsJ1($tActions);
            $tPartie->setPartieActionsJ2($tActions);
            $em = $this->getDoctrine()->getManager();
            $em->persist($tPartie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $tPartie->getId(), 'partie'=>$tPartie]);
        }

        array_multisort($maMain);
        return $this->render('Partie/afficher.html.twig',
            [   'partie' => $tPartie,
                'objets' => $tObjets,
                'objectifs' => $tObjectifs,
                'maMain' => $maMain,
                'mesActions' => $mesActions,
                'actionsAdv' => $actionsAdv,
                'mesPoints' => $mesPoints,
                'mesObjs' => $mesObjs,
                'pointsAdv' => $pointsAdv,
                'objsAdv' => $objsAdv,
                'connecte' => $connecte,
                'action_dispo'=> $tActionsDispo,
                'action_choisie'=>$action_selectionnee,
                'carte_secret'=>$carte_secret,
                'cartes_dissimulees'=>$tCartesDiss,
                'choix_cadeau'=>$tChoix_cadeau,
                'choix_paire'=>$tChoix_paire,
                'tour_cadeau'=>$tour_cadeau,
                'tour_paire'=>$tour_paire,
                'att_cadeau'=>$att_cadeau,
                'att_paire'=>$att_paire,
                'message'=>$message
            ]
        );
    }

    /**
     * @Route("/refresh/{id}", name="refresh")
     */
    public function refresh(Partie $partie) {
        if($partie->getPartieTour()  == $partie->getPartieJ1()->getId()){
            $tour = $partie->getPartieJ1()->getId();
            return new  JsonResponse($tour);
        }else{
            $tour = $partie->getPartieJ2()->getId();
            return new  JsonResponse($tour);
        }
    }

    /**
     * @Route("/chat", name="chat")
     */
    public function chat(Request $request) {
        $messages = $this->getDoctrine()->getRepository(Chat::class)->findBy([], ['id' => 'DESC'], 15);

        $tMessages = array_reverse($messages);
        
        $message = $request->request->get('message');
        if ($message != null) {
            $datetime = new \Datetime("now");
            $datetime->modify('+ 2 hour');

            $chat = new Chat();
            $chat->setChatMessage($message);
            $chat->setChatPseudo($this->getUser()->getUsername());
            $chat->setChatTime($datetime);

            $em = $this->getDoctrine()->getManager();
            //Sauvegarde mon objet Partie dans la base de données
            $em->persist($chat);
            $em->flush();
        }

        return $this->render('Partie/chat.html.twig', ['messages' => $tMessages]);
    }
}

?>