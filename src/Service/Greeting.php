<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Show Hello World Service
 *
 * @author partnerfusion
 */
class Greeting {

    private $logger;
    
    private $message;
    
    public function __construct(LoggerInterface $logger, string $message) {
        $this->logger = $logger;
        $this->message = $message;
    }
    
    public function greet(string $name): string
    {
        $this->logger->info('Greeted ' . $name);
        return $this->message . ' ' . $name;
    }
}
