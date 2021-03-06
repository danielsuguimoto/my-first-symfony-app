<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Description of LocaleSubscriber
 *
 * @author partnerfusion
 */
class LocaleSubscriber implements EventSubscriberInterface {

    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct(string $defaultLocale = 'en') {
        
        $this->defaultLocale = $defaultLocale;
    }
    
    public static function getSubscribedEvents() {
        return [
            KernelEvents::REQUEST => [
                [
                    'onKernelRequest',
                    20
                ],
            ],
        ];
    }
    
    public function onKernelRequest(GetResponseEvent $event) {
        $request = $event->getRequest();
        
        if (!$request->hasPreviousSession()) {
            return;
        }
        
        /* var string $locale */
        $locale = $request->attributes->get('_locale');
        
        if ($locale) {
            $request->getSession()->set('_locale', $locale);
        } else {
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }
}
