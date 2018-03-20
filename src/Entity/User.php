<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="user_nom")
     */
    private $userNom;

    /**
     * @ORM\Column(type="string", name="user_prenom")
     */
    private $userPrenom;

    /**
     * @ORM\Column(type="string", name="user_mail")
     */
    private $userMail;

    /**
     * @ORM\Column(type="string", name="user_pseudo")
     */
    private $userPseudo;

    /**
     * @ORM\Column(type="string", name="user_mdp")
     */
    private $userMdp;

    /**
     * @ORM\Column(type="datetime", name="user_dateInscription")
     */
    private $userDateInscription;

    /**
     * @ORM\Column(type="integer", name="user_nbVictoire")
     */
    private $userNbVictoire;

    /**
     * @ORM\Column(type="integer", name="user_nbPartie")
     */
    private $userNbPartie;

    /**
     * @ORM\Column(type="string", name="user_avatar")
     */
    private $userAvatar;

    /**
     * @ORM\Column(type="string", name="user_titre")
     */
    private $userTitre;

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
    public function getUserNom()
    {
        return $this->userNom;
    }

    /**
     * @param mixed $userNom
     */
    public function setUserNom($userNom)
    {
        $this->userNom = $userNom;
    }

    /**
     * @return mixed
     */
    public function getUserPrenom()
    {
        return $this->userPrenom;
    }

    /**
     * @param mixed $userPrenom
     */
    public function setUserPrenom($userPrenom)
    {
        $this->userPrenom = $userPrenom;
    }

    /**
     * @return mixed
     */
    public function getUserMail()
    {
        return $this->userMail;
    }

    /**
     * @param mixed $userMail
     */
    public function setUserMail($userMail)
    {
        $this->userMail = $userMail;
    }

    /**
     * @return mixed
     */
    public function getUserPseudo()
    {
        return $this->userPseudo;
    }

    /**
     * @param mixed $userPseudo
     */
    public function setUserPseudo($userPseudo)
    {
        $this->userPseudo = $userPseudo;
    }

    /**
     * @return mixed
     */
    public function getUserMdp()
    {
        return $this->userMdp;
    }

    /**
     * @param mixed $userMdp
     */
    public function setUserMdp($userMdp)
    {
        $this->userMdp = $userMdp;
    }

    /**
     * @return mixed
     */
    public function getUserDateInscription()
    {
        return $this->userDateInscription;
    }

    /**
     * @param mixed $userDateInscription
     */
    public function setUserDateInscription($userDateInscription)
    {
        $this->userDateInscription = $userDateInscription;
    }

    /**
     * @return mixed
     */
    public function getUserNbVictoire()
    {
        return $this->userNbVictoire;
    }

    /**
     * @param mixed $userNbVictoire
     */
    public function setUserNbVictoire($userNbVictoire)
    {
        $this->userNbVictoire = $userNbVictoire;
    }

    /**
     * @return mixed
     */
    public function getUserNbPartie()
    {
        return $this->userNbPartie;
    }

    /**
     * @param mixed $userNbPartie
     */
    public function setUserNbPartie($userNbPartie)
    {
        $this->userNbPartie = $userNbPartie;
    }

    /**
     * @return mixed
     */
    public function getUserAvatar()
    {
        return $this->userAvatar;
    }

    /**
     * @param mixed $userAvatar
     */
    public function setUserAvatar($userAvatar)
    {
        $this->userAvatar = $userAvatar;
    }

    /**
     * @return mixed
     */
    public function getUserTitre()
    {
        return $this->userTitre;
    }

    /**
     * @param mixed $userTitre
     */
    public function setUserTitre($userTitre)
    {
        $this->userTitre = $userTitre;
    }
}
