<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="postsLiked")
     * @ORM\JoinTable(name="post_likes",
     *      joinColumns = {
     *          @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns = {
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      }
     * )
     */
    private $likedBy;
    
    public function __construct() {
        $this->likedBy = new ArrayCollection();
    }


    public function getId() {
        return $this->id;
    }
    
    public function getText() {
        return $this->text;
    }
    
    public function setText($text) {
        $this->text = $text;
    }
    
    public function getTime() {
        return $this->time;
    }
    
    public function setTime($time) {
        $this->time = $time;
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function setTimeOnPersist() {
        $this->time = new DateTime();
    }
    
    /**
     * 
     * @return User
     */
    function getUser() {
        return $this->user;
    }

    function setUser($user) {
        $this->user = $user;
    }
    
    /**
     * 
     * @return Collection
     */
    function getLikedBy() {
        return $this->likedBy;
    }
    
    public function like(User $user) {
        if ($this->likedBy->contains($user)) {
            return;
        }
        
        $this->likedBy->add($user);
    }
}
