<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Entity\Video;
use App\Services\GiftService;
use Couchbase\GeoBoundingBoxSearchQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Events\VideoCreatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Form\VideoFormType;


class DefaultController extends AbstractController
{
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;

    }

    /**
     * @Route("/", name="default")
     */
    public function index(Request $request)
    {

           $entityManager = $this->getDoctrine()->getManager();
           $videos = $entityManager->getRepository(Video::class)->findAll();
           dump($videos);
            $video = new Video();
           $video->setTitle('Writ a blog post');
           $video->getCreatedAt(new \DateTime('tomorrow'));

        //$video = $entityManager->getRepository(Video::class)->find(1);


           $form = $this->createForm(VideoFormType::class, $video);

            $form->handleRequest($request);
           if ($form->isSubmitted() && $form->isValid())
           {
               $file = $form->get('file')->getData();
               dump($file);
               $fileName = sha1(random_bytes(14)).'.'.$file->guessExtension();
               $file->move(
                   $this->getParameter('videos_directory'),
                   $fileName
               );
               $video->setFile($fileName);
               $entityManager->persist($video);
               $entityManager->flush();
               return $this->redirectToRoute('default');
           }

       return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
           'form'=>$form->createView(),

        ]);
    }



//   /**
//    * @Route("/download")
//    */
//
//   public function download()
//   {
//       $path = $this->getParameter('download_directory');
//
//       return $this->file($path.'check.php');
//   }
//
//    /**
//     * @Route("/redirec-test")
//     */
//
//   public function redirectTest()
//   {
//       return $this->redirectToRoute('rout_to_redirect', array('param'=> 10));
//
//   }
//
//   /**
//    * @Route("/url-to-redirect/{param?}", name="rout_to_redirect")
//    */
//
//   public function methodToRedirect()
//   {
//       exit('Test redirection');
//   }
//
//   /**
//    * @Route("/forwarding-to-controller")
//    */
//
//   public function forwardingToController()
//   {
//       $response = $this->forward(
//           'App\Controller\DefaultController::methodToForwardTo',
//           array('param'=>'1')
//       );
//       return $response;
//   }
//
//   /**
//    * @Route("/url-to-forward-to/{param?}", name="rotue_to_forward_to")
//    */
//
//   public function methodToForwardTo($param)
//   {
//       exit('Test controller forwarding - '.$param);
//   }


}
