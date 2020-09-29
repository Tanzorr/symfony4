<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\SecurityUser;
use App\Entity\User;
use App\Entity\Video;
use App\Form\RegisterUserType;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class DefaultController extends AbstractController
{
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;

    }

    /**
     * @Route("/", name="default")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $user = new SecurityUser();
        $entityManager  = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository(SecurityUser::class)->findAll();
        dump($users);
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $form->get('password')->getViewData()['first'])
            );
            $user->setEmail($form->get('email')->getData());

            $entityManager->persist($user);
            $entityManager->flush();
            return  $this->redirectToRoute('default');
        }



       return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
           'form' => $form->createView(),
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
