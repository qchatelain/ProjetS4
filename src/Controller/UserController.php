<?php

// src/Controller/UserController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/profil", name="user_profil")
     */
    public function profil()
    {
        return $this->render('User/profil.html.twig');
    }

    /**
     * @Route("/classement", name="user_classement")
     */
    public function classement()
    {
        return $this->render('User/classement.html.twig');
    }
}

?>