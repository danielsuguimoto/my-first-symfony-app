<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
    public function index(UserRepository $userRepository) {
        /** User $currentUser */
        $currentUser = $this->getUser();
        
        $usersToFollow = [];
        
        if ($currentUser instanceof User) {
            $posts = $this->microPostRepository->findAllByUsers($currentUser->getFollowing());
            
            $usersToFollow = count($posts) === 0 ? 
                    $userRepository->findAllWithMoreThen5PostsExceptUser($currentUser) : [];
        } else {
            $posts = $this->microPostRepository->findBy([], ['time' => 'DESC']);
        }
        
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts,
            'usersToFollow' => $usersToFollow
        ]);
    }
    
    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     * @Security("is_granted('edit', microPost)", message="Access denied")
     * @param MicroPost $microPost
     * @param Request $request
     */
    public function edit(MicroPost $microPost, Request $request) {
//        $this->denyAccessUnlessGranted('edit', $microPost);
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
     * @Security("is_granted('delete', microPost)", message="Access denied")
     * @param MicroPost $microPost
     */
    public function delete(MicroPost $microPost) {
//        $this->denyAccessUnlessGranted('delete', $microPost);
        $this->entityManager->remove($microPost);
        $this->entityManager->flush();
        
        $this->flashBag->add('notice', 'Micro post was deleted!');
        
        return new RedirectResponse(
            $this->router->generate('micro_post_index')
        );
    }

    /**
     * @Route("/add", name="micro_post_add")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request 
     */
    public function add(Request $request) {
        $user = $this->getUser();
        $microPost = new MicroPost();
        $microPost->setUser($user);

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
     * @Route("/user/{username}", name="micro_post_user")
     */
    public function userPosts(User $userWithPosts) {
        return $this->render('micro_post/user-posts.html.twig', [
            'posts' => $this->microPostRepository->findBy(['user' => $userWithPosts], ['time' => 'DESC']),
            'user' => $userWithPosts
            //'posts' => $userWithPosts->getPosts()
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
