<?php

// src/Controller/UserController.php
namespace App\Controller;

use App\Entity\Partie;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/profil")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="profil")
     */
    public function profil()
    {
        $etat = $this->getUser()->getUserBan();
        if ($etat == 0) {
            $joueurs = $this->getDoctrine()->getRepository(User::class)->findNotEqualId($this->getUser()->getId());

            return $this->render('User/profil.html.twig', ['joueurs' => $joueurs]);
        } else {
            return $this->render('User/ban.html.twig');
        }
    }

    /**
     * @Route("/mesparties", name="mesparties")
     */
    public function mesparties()
    {
        $connecte = $this->getUser()->getId();
        $cParties = $this->getDoctrine()->getRepository(Partie::class)->findWhereCours($connecte);
        $fParties = $this->getDoctrine()->getRepository(Partie::class)->findWhereFinish($connecte);

        $fAdversaire=0;
        $cAdversaire=0;
        if ($cParties != null && $this->getUser()->getUserBan() == 0)
            foreach ($cParties as $cPartie) {
                if ($connecte == $cPartie->getPartieJ1()->getId()) {
                    $cAdversaire = $cPartie->getPartieJ2();
                } else {
                    $cAdversaire = $cPartie->getPartieJ1();
                }
        } else {
            $cParties = null;
            $cAdversaire =0;
        }

        if ($fParties != null)
            foreach ($fParties as $fPartie) {
            if ($connecte == $fPartie->getPartieJ1()->getId()) {
                $fAdversaire = $fPartie->getPartieJ2();
            } else {
                $fAdversaire = $fPartie->getPartieJ1();
            }
        } else {
            $fAdversaire =0;
        }

        return $this->render('User/mesParties.html.twig',
            ['cParties' => $cParties,
             'fParties' => $fParties,
             'connecte' => $connecte,
             'cAdversaire' => $cAdversaire,
             'fAdversaire' => $fAdversaire
            ]);
    }

    /**
     * @Route("/classement", name="classement")
     */
    public function classement()
    {
        $joueurs = $this->getDoctrine()->getRepository(User::class)->findClassement();

        $tJoueurs = array();
        $place = 0;
        foreach ($joueurs as $joueur) {
            if($joueur->getUserNbPartie() != 0) {
                if ($joueur->getUserNbPartie() != $joueur->getUserNbVictoire()) {
                    $ratio = $joueur->getUserNbVictoire()/($joueur->getUserNbPartie()-$joueur->getUserNbVictoire());
                } else {
                    $ratio = $joueur->getUserNbVictoire();
                }
            } else {
                $ratio = 0;
            }
            $place ++;
            $id = array();
            $id['place'] = $place;
            $id['pseudo'] = $joueur->getUsername();
            $id['victoire'] = $joueur->getUserNbVictoire();
            $id['ratio'] = round($ratio, 2);
            $id['avatar'] = $joueur->getUserAvatar();
            $tJoueurs[$joueur->getId()] = $id;
        }

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $connecte = null;
        } else {
            $connecte = $tJoueurs[$this->getUser()->getId()];
        }

        return $this->render('User/classement.html.twig',
            ['joueurs' => $tJoueurs,
             'connecte' => $connecte
            ]);
    }

    /**
     * @Route("/compte", name="compte")
     */
    public function compte(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            // On enregistre l'utilisateur dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->render('User/compte.html.twig', array('form' => $form->createView(), 'user' => $user));
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin(AuthorizationCheckerInterface $authChecker)
    {
        if (false === $authChecker->isGranted('ROLE_ADMIN')) {
            if (false === $authChecker->isGranted('ROLE_USER')) {
                return $this->redirectToRoute('login');
            } else {
                return $this->redirectToRoute('profil');
            }
        } else {
            $connecte = $this->getUser()->getId();
            $joueurs = $this->getDoctrine()->getRepository(User::class)->findNotEqualId($connecte);

            return $this->render('User/admin.html.twig',
                ['joueurs' => $joueurs,
                ]);
        }
    }

    /**
     * @Route("/admin/etat/{id}", name="etat")
     */
    public function etat(AuthorizationCheckerInterface $authChecker, User $user)
    {
        if (false === $authChecker->isGranted('ROLE_ADMIN')) {
            if (false === $authChecker->isGranted('ROLE_USER')) {
                return $this->redirectToRoute('login');
            } else {
                return $this->redirectToRoute('profil');
            }
        } else {
            $joueur = $this->getDoctrine()->getRepository(User::class)->find($user);

            $etat = $joueur->getUserBan();
            if ($etat == 0) {
                $etat = 1;
            } else {
                $etat = 0;
            }
            $joueur->setUserBan($etat);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin');
        }
    }
}

?>