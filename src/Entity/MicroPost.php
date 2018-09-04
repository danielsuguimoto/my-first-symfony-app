<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=280)
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     */
    private $text;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $time;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn()
     */
    private $user;
    
    /**
     * 
     * @return type
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * 
     * @return type
     */
    public function getText() {
        return $this->text;
    }
    
    /**
     * 
     * @param type $text
     */
    public function setText($text) {
        $this->text = $text;
    }
    
    /**
     * 
     * @return type
     */
    public function getTime() {
        return $this->time;
    }
    
    /**
     * 
     * @param type $time
     */
    public function setTime($time) {
        $this->time = $time;
    }
    
    function getUser() {
        return $this->user;
    }

    function setUser($user) {
        $this->user = $user;
    }
}
