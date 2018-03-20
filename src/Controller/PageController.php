<?php

// src/Controller/BlogController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    /**
     * @Route("/page/cours", name="page_cours")
     */
    public function cours()
    {
        return $this->render('page/cours.html.twig');
    }

    /**
     * @Route("/page/exo", name="page_exo")
     */
    public function exo()
    {
        return $this->render('page/exo.html.twig');
    }
}

?>