<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Tests\Mailer;

use App\Entity\User;
use App\Mailer\Mailer;
use PHPUnit\Framework\TestCase;

/**
 * Description of MailerTest
 *
 * @author partnerfusion
 */
class MailerTest extends TestCase {

    public function testConfirmationEmail() {
        $user = new User();
        $user->setEmail('john@doe.com');

        $swiftMailer = $this->getMockBuilder(\Swift_Mailer::class)->disableOriginalConstructor()->getMock();

        $swiftMailer->expects($this->once())->method('send')
                ->with($this->callback(function ($subject) {
                            $messageStr = (string) $subject;
                            return strpos($messageStr, 'From: me@domain.com') !== false 
                                    && strpos($messageStr, 'Content-Type: text/html; charset=utf-8') !== false 
                                    && strpos($messageStr, 'Subject: Welcome to the micro-post app!') !== false 
                                    && strpos($messageStr, 'To: john@doe.com') !== false
                                    && strpos($messageStr, 'This is a message body') !== false;
                        }));

        $twig = $this->getMockBuilder(\Twig_Environment::class)->disableOriginalConstructor()->getMock();
        $twig->expects($this->once())
                ->method('render')
                ->with('email/registration.html.twig', [
                    'user' => $user
        ])->willReturn('This is a message body');

        $mailer = new Mailer($swiftMailer, $twig, 'me@domain.com');
        $mailer->sendConfirmationEmail($user);
    }

}
