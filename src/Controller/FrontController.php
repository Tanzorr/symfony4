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
       $videos = $this->getDoctrine()->getRepository(Video::class)->findByChildIds($ids, $page,
           $request->get('sortby'));

       return $this->render('front/video_list.html.twig',
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
        dump($repo->videoDetails($video));
        return $this->render('front/video_details.html.twig',[
            'video'=>$repo->videoDetails($video)
            ]);
    }

    /**
     * @Route ("/new-comment/{video}", methods={"POST"}, name = "new_comment")
     */

    public function newComment(Video $video, Request $request)
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_REMEMBERED");
        if (!empty(trim($request->request->get('comment'))))
        {
            $comment = new Comment();
            $comment->setComment($request->request->get('comment'));
            $comment->setUser($this->getUser());
            $comment->setVideo($video);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
        }

        return $this->redirectToRoute('video-details',['video'=>$video->getId()]);
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

    /**
     * @Route("/video-list/{video}/like", name="like_video", methods={"POST"})
     * @Route("/video-list/{video}/dislike", name="dislike_video", methods={"POST"})
     * @Route("/video-list/{video}/unlike", name="undo_like_video", methods={"POST"})
     * @Route("/video-list/{video}/undodislike", name="undo_dislike_video", methods={"POST"})
     */

    public function toggleLikesAjax(Video $video, Request $request) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        switch ($request->get('_route'))
        {
            case 'like_video':
                $result = $this->likeVideo($video);
                break;
                case 'dislike_video':
                $result = $this->dislikeVideo($video);
                break;
            case 'undo_like_video':
                $result = $this->undoLikeVideo($video);
                break;
            case 'undo_dislike_video':
                $result = $this->undoDisLikeVideo($video);
                break;
        }

        return $this->json(['action'=>$result, 'id'=>$video->getId()]);
    }

    public function likeVideo($video)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser);
        $user->addLikedVideo($video);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return 'liked';
    }

    public function dislikeVideo($video)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser);
        $user->addDislikeVideo($video);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return 'disliked';
    }

    public function undoLikeVideo($video)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser);
        $user->removeLikedVideo($video);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return 'undo liked';
    }

    public function undoDislikeVideo($video)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser);
        $user->removeDislikeVideo($video);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return 'undo disliked';
    }



    public  function mainCategories() {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy(['parent'=>null],['name'=>'ASC']);
        return $this->render('front/_main_categories.html.twig',
                                ['categories' => $categories]);
    }



}
