<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Mailer;

use App\Entity\User;

/**
 * Description of Mailer
 *
 * @author partnerfusion
 */
class Mailer {

    /**
     * @var string
     */
    private $emailFrom;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, string $emailFrom) {
        
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->emailFrom = $emailFrom;
    }
    
    public function sendConfirmationEmail(User $user) {
        $body = $this->twig->render('email/registration.html.twig', [
            'user' => $user
        ]);
        
        $message = (new \Swift_Message())
            ->setSubject('Welcome to the micro-post app!')
            ->setFrom($this->emailFrom)
            ->setTo($user->getEmail())
            ->setBody($body, 'text/html');
        
        $this->mailer->send($message);
    }
}
