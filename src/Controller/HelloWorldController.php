<?php

namespace App\Controller;

use App\Service\Greeting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HelloWorldController extends AbstractController
{

    /**
     * @Route("/", name="hello_index")
     */
    public function index(Request $request, Greeting $greeting)
    {
        $name = $request->get('name');
        
        return $this->render('hello_world/index.html.twig', [
            'hello' => $name ? $greeting->greet($name) : 'Hello world!',
        ]);
    }
}
