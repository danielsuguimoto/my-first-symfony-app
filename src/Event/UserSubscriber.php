<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Event;

use App\Entity\UserPreferences;
use App\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of UserSubscriber
 *
 * @author partnerfusion
 */
class UserSubscriber implements EventSubscriberInterface {

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer, EntityManagerInterface $entityManager, string $defaultLocale) {
        
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->defaultLocale = $defaultLocale;
    }
    
    public static function getSubscribedEvents(): array {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }
    
    public function onUserRegister(UserRegisterEvent $event) {
        $preferences = new UserPreferences();
        $preferences->setLocale($this->defaultLocale);
        
        $user = $event->getRegisteredUser();
        $user->setPreferences($preferences);
        
        $this->entityManager->flush();
        
        $this->mailer->sendConfirmationEmail($user);
    }
}
