<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Utils\CategoryTreeFrontPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Video;
use App\Repository\VideoRepository;
use App\Controller\Traits\Likes;
use App\Utils\VideoForNoValidSubscriptionFile;


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
    public function videoList($id, $page, CategoryTreeFrontPage $categories, Request $request,
    VideoForNoValidSubscriptionFile $validSubscriptionFile )
    {
       $categories->getCategoryListParent($id);
       $ids = $categories->getChildIds($id);
       array_push($ids, $id);
       $videos = $this->getDoctrine()->getRepository(Video::class)->findByChildIds($ids, $page,
           $request->get('sortby'));

       return $this->render('front/video_list.html.twig',
            ['subcategories' => $categories,
             'videos'=>$videos,
              'video_no_members'=>$validSubscriptionFile->check()
            ]
        );
    }

    /**
     * @Route("/video-details/{video}", name="video-details")
     */
    public function videoDetails(VideoRepository $repo, $video, VideoForNoValidSubscriptionFile $validSubscriptionFile)
    {
        dump($repo->videoDetails($video));
        return $this->render('front/video_details.html.twig',[
            'video'=>$repo->videoDetails($video),
            'video_no_members'=>$validSubscriptionFile->check()
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
    public function searchResults($page, Request $request, VideoForNoValidSubscriptionFile $validSubscriptionFile)
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
            'query'=>$query,
            'video_no_members'=>$validSubscriptionFile->check()
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
    use Likes;
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





    public  function mainCategories() {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy(['parent'=>null],['name'=>'ASC']);
        return $this->render('front/_main_categories.html.twig',
                                ['categories' => $categories]);
    }



}
