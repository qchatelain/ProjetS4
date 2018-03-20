<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 */
class Chat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time", name="chat_time")
     */
    private $chatTime;

    /**
     * @ORM\Column(type="string", name="chat_message")
     */
    private $chatMessage;

    /**
     * @ORM\Column(type="string", name="chat_pseudo")
     */
    private $chatPseudo;

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
    public function getChatTime()
    {
        return $this->chatTime;
    }

    /**
     * @param mixed $chatTime
     */
    public function setChatTime($chatTime)
    {
        $this->chatTime = $chatTime;
    }

    /**
     * @return mixed
     */
    public function getChatMessage()
    {
        return $this->chatMessage;
    }

    /**
     * @param mixed $chatMessage
     */
    public function setChatMessage($chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * @return mixed
     */
    public function getChatPseudo()
    {
        return $this->chatPseudo;
    }

    /**
     * @param mixed $chatPseudo
     */
    public function setChatPseudo($chatPseudo)
    {
        $this->chatPseudo = $chatPseudo;
    }
}
