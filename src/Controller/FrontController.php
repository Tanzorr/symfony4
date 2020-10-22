<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Utils\CategoryTreeFrontPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Video;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\VideoRepository;


class FrontController extends AbstractController
{
    /**
     * @Route("/", name="main_page")
     */
    public function index()
    {
        return $this->render('front/index.html.twig');
    }

    /**
     * @Route("/video-list/category/{categoryname},{id}/{page}", defaults = {"page":1}, name="video_list")
     */
    public function videoList($id, $page, CategoryTreeFrontPage $categories, Request $request)
    {
       $categories->getCategoryListParent($id);
       $ids = $categories->getChildIds($id);
       array_push($ids, $id);
       $videos = $this->getDoctrine()->getRepository(Video::class)->findByPaginate($ids, $page,
           $request->get('sortby'));

       return $this->render('front/video_list.htm.twig',
            ['subcategories' => $categories,
             'videos'=>$videos
            ]
        );
    }

    /**
     * @Route("/video-details/{video}", name="video-details")
     */
    public function videoDetails(VideoRepository $repo, $video)
    {
        return $this->render('front/video_details.htm.twig',[
            'video'=>$repo->videoDetails($video)
            ]);
    }

    /**
     * @Route("/search-results", methods={"GET"}, name="search-results", defaults={"page": 1})
     */
    public function searchResults($page, Request $request)
    {
        $query = null;
        $videos = null;
        if ($query = $request->get('query'))
        {
            $videos = $this->getDoctrine()
                ->getRepository(Video::class)
                ->findByTitle($query, $page, $request->get('sortby'));
            if (!$videos->getItems()) $videos = null;
        }

        return $this->render('front/search_results.html.twig',[
            'videos'=>$videos,
            'query'=>$query
        ]);


    }

    /**
     * @Route("/pricing",  name="pricing")
     */
    public function pricing()
    {
        return $this->render('front/pricing.html.twig');
    }

    /**
     * @Route("/register",  name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $password_encoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() )
        {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setName($request->request->get('user')['name']);
            $user->setLastName($request->request->get('user')['last_name']);
            $user->setEmail($request->request->get('user')['email']);
            $password = $password_encoder->encodePassword($user,
            $request->request->get('user')['password']['first']);
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->loginUserAutomatically($user, $password);

            return $this->redirectToRoute('admin_main_page');
        }
        return $this->render('front/register.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/login",  name="login")
     */
    public function login(AuthenticationUtils $helper)
    {
        return $this->render('front/login.html.twig', [
            'error' => $helper->getLastAuthenticationError()
        ]);
    }

    private function loginUserAutomatically($user, $password)
    {
        $token = new UsernamePasswordToken(
            $user,
            $password,
            'main',
            $user->getRoles()
        );

        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));
    }

    /*
     * @Route("/logout", name="logout")
     */

    public function logout():void
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/payment",  name="payment")
     */
    public function payment()
    {
        return $this->render('front/payment.html.twig');
    }

    public  function mainCategories() {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy(['parent'=>null],['name'=>'ASC']);
        return $this->render('front/_main_categories.html.twig',
                                ['categories' => $categories]);
    }



}
