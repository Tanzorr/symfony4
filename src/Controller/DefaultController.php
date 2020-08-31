<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\GiftService;
use Couchbase\GeoBoundingBoxSearchQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(GiftService $gifts)
    {
       $users = $this->getDoctrine()->getRepository(User::class)->findAll();
       if(!isset($users)) {
           $users = [];
       }

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'users' => $users,
            'random_gift'=>$gifts->gifts,
        ]);
    }
}
