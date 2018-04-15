<?php

// src/Controller/AcuueilController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccueilController extends Controller
{
    /**
    * @Route("/", name="accueil")
    */
    public function accueil()
    {
        return $this->render('Accueil/accueil.html.twig');
    }

    /**
     * @Route("/plateau", name="plateau")
     */
    public function plateau()
    {
        return $this->render('Accueil/plateau.html.twig');
    }

    /**
     * @Route("/regles", name="regles")
     */
    public function regles()
    {
        return $this->render('Accueil/regles.html.twig');
    }
}

?>