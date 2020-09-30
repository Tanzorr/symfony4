<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\SecurityUser;
use App\Entity\User;
use App\Entity\Video;
use App\Form\RegisterUserType;
use App\Services\GiftService;
use Couchbase\GeoBoundingBoxSearchQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Translation\TranslatorInterface;


class DefaultController extends AbstractController
{
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;

    }

    /**
     * @Route("/", name="home")
     *
     */
    public function index(Request $request,TranslatorInterface $translator)
    {
        //$entityManager  = $this->getDoctrine()->getManager();

        $translated = $translator->trans('some.key');
        dump($translated);

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

     /**
      * @Route({
      *     "en": "/login",
      *     "pl": "/logowanie",

*     }, name="login")
      */

    public function login(AuthenticationUtils $authenticationUtils) {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_name' => $lastUsername,
            'error' => $error,
        ));

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
