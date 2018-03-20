<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObjetRepository")
 */
class Objet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="objet_points")
     */
    private $objetPoints;

    /**
     * @ORM\Column(type="string", name="objet_nom")
     */
    private $objetNom;

    /**
     * @ORM\Column(type="string", name="objet_img")
     */
    private $objetImg;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Objectif")
     */
    private $objectifId;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getObjetPoints()
    {
        return $this->objetPoints;
    }

    /**
     * @param mixed $objetPoints
     */
    public function setObjetPoints($objetPoints)
    {
        $this->objetPoints = $objetPoints;
    }

    /**
     * @return mixed
     */
    public function getObjetNom()
    {
        return $this->objetNom;
    }

    /**
     * @param mixed $objetNom
     */
    public function setObjetNom($objetNom)
    {
        $this->objetNom = $objetNom;
    }

    /**
     * @return mixed
     */
    public function getObjetImg()
    {
        return $this->objetImg;
    }

    /**
     * @param mixed $objetImg
     */
    public function setObjetImg($objetImg)
    {
        $this->objetImg = $objetImg;
    }

    /**
     * @return mixed
     */
    public function getObjectifId()
    {
        return $this->objectifId;
    }

    /**
     * @param mixed $objectifId
     */
    public function setObjectifId($objectifId)
    {
        $this->objectifId = $objectifId;
    }
}
