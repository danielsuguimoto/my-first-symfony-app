<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\DataFixtures;

use App\Entity\MicroPost;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Description of AppFixtures
 *
 * @author partnerfusion
 */
class AppFixtures extends Fixture {
    
    const USERS = [
        [
            'username' => 'john_doe',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob12345',
            'fullName' => 'Rob Smith',
        ],
        [
            'username' => 'marry_gold',
            'email' => 'marry_gold@gold.com',
            'password' => 'marry12345',
            'fullName' => 'Marry Gold',
        ],
    ];

    const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];
    
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        ;
        $this->passwordEncoder = $passwordEncoder;
    }

        //put your code here
    public function load(ObjectManager $manager) {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }
    
    private function loadMicroPosts(ObjectManager $manager) {
        for ($i = 0; $i < 30; $i++) {
            $microPost = new MicroPost();
            $microPost->setText(self::POST_TEXT[rand(0, count(self::POST_TEXT) - 1)]);
            
            $date = new \DateTime();
            $date->modify('-' . rand(0, 10) . ' day');
            
            $microPost->setTime($date);
            $microPost->setUser($this->getReference(self::USERS[rand(0, count(self::USERS) - 1)]['username']));
            $manager->persist($microPost);
        }
        
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager) {
        foreach (self::USERS as $userData) {
            $user = new \App\Entity\User();
            $user->setUsername($userData['username']);
            $user->setFullname($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userData['password']));

            $this->addReference($userData['username'], $user);

            $manager->persist($user);
            $manager->flush();
        }
    }
}
