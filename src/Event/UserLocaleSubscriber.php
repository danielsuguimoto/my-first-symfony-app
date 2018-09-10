<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use App\Entity\User;

/**
 * Description of UserLocaleSubscriber
 *
 * @author partnerfusion
 */
class UserLocaleSubscriber implements EventSubscriberInterface {

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session) {
        
        $this->session = $session;
    }

    public static function getSubscribedEvents(): array {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => [
                [
                    'onInteractiveLogin',
                    15
                ]
            ]
        ];
    }
    
    public function onInteractiveLogin(InteractiveLoginEvent $event) {
        /* @var User $user */
        $user = $event->getAuthenticationToken()->getUser();
        
        $this->session->set('_locale', $user->getPreferences()->getLocale());
    }
}
