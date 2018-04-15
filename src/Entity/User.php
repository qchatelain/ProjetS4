<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email déjà pris")
 * @UniqueEntity(fields="username", message="Pseudo déjà pris")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="user_nom")
     * @Assert\NotBlank()
     */
    private $userNom;

    /**
     * @ORM\Column(type="string", name="user_prenom")
     * @Assert\NotBlank()
     */
    private $userPrenom;

    /**
     * @ORM\Column(type="string", name="user_mail", unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", name="user_pseudo", unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", name="user_mdp")
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="text", name="user_roles")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="datetime", name="user_dateInscription", nullable=true)
     */
    private $userDateInscription;

    /**
     * @ORM\Column(type="integer", name="user_nbVictoire")
     */
    private $userNbVictoire = 0;

    /**
     * @ORM\Column(type="integer", name="user_nbPartie")
     */
    private $userNbPartie = 0;

    /**
     * @ORM\Column(type="string", name="user_avatar")
     */
    private $userAvatar = "";

    /**
     * @ORM\Column(type="string", name="user_titre")
     */
    private $userTitre = "";

    /**
     * @ORM\Column(type="integer", name="user_ban")
     */
    private $userBan = 0;

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

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        $roles = json_decode($this->roles);

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = json_encode($roles);
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

    /**
     * @return mixed
     */
    public function getUserBan()
    {
        return $this->userBan;
    }

    /**
     * @param mixed $userBan
     */
    public function setUserBan($userBan)
    {
        $this->userBan = $userBan;
    }

    /**
     * Retour le salt qui a servi à coder le mot de passe
     *
     * {@inheritdoc}
     */
    public function getSalt()
    {
        // See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one

        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        // Nous n'avons pas besoin de cette methode car nous n'utilions pas de plainPassword
        // Mais elle est obligatoire car comprise dans l'interface UserInterface
        // $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([$this->id, $this->userPseudo, $this->userMdp]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        [$this->id, $this->userPseudo, $this->userMdp] = unserialize($serialized, ['allowed_classes' => false]);
    }
}
