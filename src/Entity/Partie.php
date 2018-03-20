<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartieRepository")
 */
class Partie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", name="partie_date")
     */
    private $partieDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $partieJ1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $partieJ2;

    /**
     * @ORM\Column(type="integer", name="partie_carteEcart")
     */
    private $partieCarteEcart;

    /**
     * @ORM\Column(type="json", name="partie_mainJ1")
     */
    private $partieMainJ1;

    /**
     * @ORM\Column(type="json", name="partie_mainJ2")
     */
    private $partieMainJ2;

    /**
     * @ORM\Column(type="json", name="partie_pioche")
     */
    private $partiePioche;

    /**
     * @ORM\Column(type="json", name="partie_terrainJ1", nullable=true)
     */
    private $partieTerrainJ1;

    /**
     * @ORM\Column(type="json", name="partie_terrainJ2", nullable=true)
     */
    private $partieTerrainJ2;

    /**
     * @ORM\Column(type="json", name="partie_actionsJ1", nullable=true)
     */
    private $partieActionsJ1;

    /**
     * @ORM\Column(type="json", name="partie_actionsJ2", nullable=true)
     */
    private $partieActionsJ2;

    /**
     * @ORM\Column(type="integer", name="partie_tour")
     */
    private $partieTour;

    /**
     * @ORM\Column(type="integer", name="partie_manche")
     */
    private $partieManche = 1;

    /**
     * @ORM\Column(type="json", name="partie_objectifs", nullable=true)
     */
    private $partieObjectifs;

    /**
     * @ORM\Column(type="integer", name="partie_pointsJ1")
     */
    private $partiePointsJ1 = 0;

    /**
     * @ORM\Column(type="integer", name="partie_pointsJ2")
     */
    private $partiePointsJ2 = 0;

    /**
     * @ORM\Column(type="integer", name="partie_nbObjJ1")
     */
    private $partieNbObjJ1 = 0;

    /**
     * @ORM\Column(type="integer", name="partie_nbObjJ2")
     */
    private $partieNbObjJ2 = 0;

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
    public function getPartieDate()
    {
        return $this->partieDate;
    }

    /**
     * @param mixed $partieDate
     */
    public function setPartieDate($partieDate)
    {
        $this->partieDate = $partieDate;
    }

    /**
     * @return mixed
     */
    public function getPartieJ1()
    {
        return $this->partieJ1;
    }

    /**
     * @param mixed $partieJ1
     */
    public function setPartieJ1($partieJ1)
    {
        $this->partieJ1 = $partieJ1;
    }

    /**
     * @return mixed
     */
    public function getPartieJ2()
    {
        return $this->partieJ2;
    }

    /**
     * @param mixed $partieJ2
     */
    public function setPartieJ2($partieJ2)
    {
        $this->partieJ2 = $partieJ2;
    }

    /**
     * @return mixed
     */
    public function getPartieCarteEcart()
    {
        return $this->partieCarteEcart;
    }

    /**
     * @param mixed $partieCarteEcart
     */
    public function setPartieCarteEcart($partieCarteEcart)
    {
        $this->partieCarteEcart = $partieCarteEcart;
    }

    /**
     * @return mixed
     */
    public function getPartieMainJ1()
    {
        return json_decode($this->partieMainJ1);
    }

    /**
     * @param mixed $partieMainJ1
     */
    public function setPartieMainJ1($partieMainJ1)
    {
        $this->partieMainJ1 = json_encode($partieMainJ1);
    }

    /**
     * @return mixed
     */
    public function getPartieMainJ2()
    {
        return json_decode($this->partieMainJ2);
    }

    /**
     * @param mixed $partieMainJ2
     */
    public function setPartieMainJ2($partieMainJ2)
    {
        $this->partieMainJ2 = json_encode($partieMainJ2);
    }

    /**
     * @return mixed
     */
    public function getPartiePioche()
    {
        return json_decode($this->partiePioche);
    }

    /**
     * @param mixed $partiePioche
     */
    public function setPartiePioche($partiePioche)
    {
        $this->partiePioche = json_encode($partiePioche);
    }

    /**
     * @return mixed
     */
    public function getPartieTerrainJ1()
    {
        return  json_decode($this->partieTerrainJ1);
    }

    /**
     * @param mixed $partieTerrainJ1
     */
    public function setPartieTerrainJ1($partieTerrainJ1)
    {
        $this->partieTerrainJ1 = json_encode($partieTerrainJ1);
    }

    /**
     * @return mixed
     */
    public function getPartieTerrainJ2()
    {
        return json_decode($this->partieTerrainJ2);
    }

    /**
     * @param mixed $partieTerrainJ2
     */
    public function setPartieTerrainJ2($partieTerrainJ2)
    {
        $this->partieTerrainJ2 = json_encode($partieTerrainJ2);
    }

    /**
     * @return mixed
     */
    public function getPartieActionsJ1()
    {
        return json_decode($this->partieActionsJ1);
    }

    /**
     * @param mixed $partieActionsJ1
     */
    public function setPartieActionsJ1($partieActionsJ1)
    {
        $this->partieActionsJ1 = json_encode($partieActionsJ1);
    }

    /**
     * @return mixed
     */
    public function getPartieActionsJ2()
    {
        return json_decode($this->partieActionsJ2);
    }

    /**
     * @param mixed $partieActionsJ2
     */
    public function setPartieActionsJ2($partieActionsJ2)
    {
        $this->partieActionsJ2 = json_encode($partieActionsJ2);
    }

    /**
     * @return mixed
     */
    public function getPartieTour()
    {
        return $this->partieTour;
    }

    /**
     * @param mixed $partieTour
     */
    public function setPartieTour($partieTour)
    {
        $this->partieTour = $partieTour;
    }

    /**
     * @return mixed
     */
    public function getPartieManche()
    {
        return $this->partieManche;
    }

    /**
     * @param mixed $partieManche
     */
    public function setPartieManche($partieManche)
    {
        $this->partieManche = $partieManche;
    }

    /**
     * @return mixed
     */
    public function getPartieObjectifs()
    {
        return json_decode($this->partieObjectifs);
    }

    /**
     * @param mixed $partieObjectifs
     */
    public function setPartieObjectifs($partieObjectifs)
    {
        $this->partieObjectifs = json_encode($partieObjectifs);
    }

    /**
     * @return mixed
     */
    public function getPartiePointsJ1()
    {
        return $this->partiePointsJ1;
    }

    /**
     * @param mixed $partiePointsJ1
     */
    public function setPartiePointsJ1($partiePointsJ1)
    {
        $this->partiePointsJ1 = $partiePointsJ1;
    }

    /**
     * @return mixed
     */
    public function getPartiePointsJ2()
    {
        return $this->partiePointsJ2;
    }

    /**
     * @param mixed $partiePointsJ2
     */
    public function setPartiePointsJ2($partiePointsJ2)
    {
        $this->partiePointsJ2 = $partiePointsJ2;
    }

    /**
     * @return mixed
     */
    public function getPartieNbObjJ1()
    {
        return $this->partieNbObjJ1;
    }

    /**
     * @param mixed $partieNbObjJ1
     */
    public function setPartieNbObjJ1($partieNbObjJ1)
    {
        $this->partieNbObjJ1 = $partieNbObjJ1;
    }

    /**
     * @return mixed
     */
    public function getPartieNbObjJ2()
    {
        return $this->partieNbObjJ2;
    }

    /**
     * @param mixed $partieNbObjJ2
     */
    public function setPartieNbObjJ2($partieNbObjJ2)
    {
        $this->partieNbObjJ2 = $partieNbObjJ2;
    }
}
