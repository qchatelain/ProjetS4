<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObjectifRepository")
 */
class Objectif
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="objectif_points")
     */
    private $objectifPoints;

    /**
     * @ORM\Column(type="string", name="objectif_nom")
     */
    private $objectifNom;

    /**
     * @ORM\Column(type="string", name="objectif_img")
     */
    private $objectifImg;

    /**
     * @ORM\Column(type="string", name="objectif_pion")
     */
    private $objectifPion;

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
    public function getObjectifPoints()
    {
        return $this->objectifPoints;
    }

    /**
     * @param mixed $objectifPoints
     */
    public function setObjectifPoints($objectifPoints)
    {
        $this->objectifPoints = $objectifPoints;
    }

    /**
     * @return mixed
     */
    public function getObjectifNom()
    {
        return $this->objectifNom;
    }

    /**
     * @param mixed $objectifNom
     */
    public function setObjectifNom($objectifNom)
    {
        $this->objectifNom = $objectifNom;
    }

    /**
     * @return mixed
     */
    public function getObjectifImg()
    {
        return $this->objectifImg;
    }

    /**
     * @param mixed $objectifImg
     */
    public function setObjectifImg($objectifImg)
    {
        $this->objectifImg = $objectifImg;
    }

    /**
     * @return mixed
     */
    public function getObjectifPion()
    {
        return $this->objectifPion;
    }

    /**
     * @param mixed $objectifPion
     */
    public function setObjectifPion($objectifPion)
    {
        $this->objectifPion = $objectifPion;
    }

}
