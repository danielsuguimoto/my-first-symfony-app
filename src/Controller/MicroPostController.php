<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/micro_post")
 */
class MicroPostController extends AbstractController {

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;

    public function __construct(
        MicroPostRepository $microPostRepository, FormFactoryInterface $formFactory, 
        EntityManagerInterface $entityManager, RouterInterface $router, FlashBagInterface $flashBag
    ) {

        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/", name="micro_post_index")
     */
    public function index() {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $this->microPostRepository->findBy([], ['time' => 'DESC']),
        ]);
    }
    
    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     * @param MicroPost $microPost
     * @param Request $request
     */
    public function edit(MicroPost $microPost, Request $request) {
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->flashBag->add('notice', 'Micro post was changed!');
            
            return new RedirectResponse(
                $this->router->generate('micro_post_index')
            );
        }

        return $this->render('micro_post/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     * @param MicroPost $microPost
     */
    public function delete(MicroPost $microPost) {
        $this->entityManager->remove($microPost);
        $this->entityManager->flush();
        
        $this->flashBag->add('notice', 'Micro post was deleted!');
        
        return new RedirectResponse(
            $this->router->generate('micro_post_index')
        );
    }

    /**
     * @Route("/add", name="micro_post_add")
     * @param Request $request 
     */
    public function add(Request $request) {
        $microPost = new MicroPost();
        $microPost->setTime(new DateTime());

        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            $this->flashBag->add('notice', 'Micro post was added!');
            
            return new RedirectResponse(
                $this->router->generate('micro_post_index')
            );
        }

        return $this->render('micro_post/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/{id}", name="micro_post_post")
     * @param type $id
     */
    public function post(MicroPost $post) {        
        return $this->render('micro_post/post.html.twig', [
            'post' => $post
        ]);
    }
}
