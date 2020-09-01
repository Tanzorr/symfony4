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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class DefaultController extends AbstractController
{
    public function __construct($logger)
    {
        //use $logger
    }

    /**
     * @Route("/", name="default")
     */
    public function index(GiftService $gifts, Request $request)
    {
       $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'users' => $users,
            'random_gift'=>$gifts->gifts,
        ]);
    }

//   /**
//    * @Route("/generate-url/{param?}", name="generate_url")
//    */
//
//   public function generateUrl()
//   {
//       exit($this->generateUrl(
//           'generate_url',
//           array('param'=> 10),
//           UrlGeneratorInterface::ABSOLUTE_URL
//       ));
//   }

   /**
    * @Route("/download")
    */

   public function download()
   {
       $path = $this->getParameter('download_directory');

       return $this->file($path.'check.php');
   }

    /**
     * @Route("/redirec-test")
     */

   public function redirectTest()
   {
       return $this->redirectToRoute('rout_to_redirect', array('param'=> 10));

   }

   /**
    * @Route("/url-to-redirect/{param?}", name="rout_to_redirect")
    */

   public function methodToRedirect()
   {
       exit('Test redirection');
   }

   /**
    * @Route("/forwarding-to-controller")
    */

   public function forwardingToController()
   {
       $response = $this->forward(
           'App\Controller\DefaultController::methodToForwardTo',
           array('param'=>'1')
       );
       return $response;
   }

   /**
    * @Route("/url-to-forward-to/{param?}", name="rotue_to_forward_to")
    */

   public function methodToForwardTo($param)
   {
       exit('Test controller forwarding - '.$param);
   }


}
