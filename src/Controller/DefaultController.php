<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\GiftService;
use Couchbase\GeoBoundingBoxSearchQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(GiftService $gifts, Request $request, Session $session)
    {
       $users = $this->getDoctrine()->getRepository(User::class)->findAll();
       if(!isset($users)) {
           $users = [];
       }
       exit($request->query->get('page', 'default'));

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'users' => $users,
            'random_gift'=>$gifts->gifts,
        ]);
    }

    /**
     * @Route("/blog/{page?}", name="blog_list", requirements={"page"="\d+"})
     */

    public function index2()
    {
        return new Response("Optional parameters in url and requirements for parameters");
    }
}
