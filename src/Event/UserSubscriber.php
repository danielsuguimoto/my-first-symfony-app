<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Event;

use App\Mailer\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of UserSubscriber
 *
 * @author partnerfusion
 */
class UserSubscriber implements EventSubscriberInterface {

    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer) {
        
        $this->mailer = $mailer;
    }
    
    public static function getSubscribedEvents(): array {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }
    
    public function onUserRegister(UserRegisterEvent $event) {
        $this->mailer->sendConfirmationEmail($event->getRegisteredUser());
    }
}
