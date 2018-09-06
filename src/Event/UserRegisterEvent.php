<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Event;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of UserRegisterEvent
 *
 * @author partnerfusion
 */
class UserRegisterEvent extends Event{
    const NAME = 'user.register';
    /**
     * @var User
     */
    private $registeredUser;

    public function __construct(User $registeredUser) {
      
        $this->registeredUser = $registeredUser;
    }
    
    function getRegisteredUser(): User {
        return $this->registeredUser;
    }
}
