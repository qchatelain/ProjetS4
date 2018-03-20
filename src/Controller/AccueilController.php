<?php

// src/Controller/AccueilController.php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccueilController extends Controller
{
    /**
    * @Route("/", name="accueil")
    */
    public function index()
    {
        return $this->render('base.html.twig');
    }
}

?>