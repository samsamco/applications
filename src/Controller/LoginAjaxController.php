<?php


namespace App\Controller;


use App\Entity\User;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Service\TokenChecker;


class LoginAjaxController extends  BaseController
{
    private $eventDispatcher;

    private $userManager;
    private $tokenStorage;
    private  $encodeFactory;

    public function __construct(EncoderFactoryInterface $encodeFactory,EventDispatcherInterface $eventDispatcher, UserManagerInterface $userManager, TokenStorageInterface $tokenStorage)
    {
        $this->eventDispatcher = $eventDispatcher;

        $this->userManager = $userManager;
        $this->tokenStorage = $tokenStorage;
        $this->encodeFactory = $encodeFactory;
    }



    /**
     * @Route("/login_ajax", name="login_ajax")
     */
    public function  loginAction(Request $request)
    {
        $checker = new TokenChecker();

        $token = $request->get('token');

        $vtoken = $checker->checkToken($token);

        if(!$vtoken)
            return new Response(json_encode(-1));

        // This data is most likely to be retrieven from the Request object (from Form)
        // But to make it easy to understand ...
        $_email = $request->get('email');
        $_password = $request->get('password');

        // Retrieve the security encoder of symfony
        $factory = $this->encodeFactory;

        /// Start retrieve user
        // Let's retrieve the user by its username:
        // If you are using FOSUserBundle:
        $user_manager = $this->userManager;
        $user = $user_manager->findUserByEmail($_email);
        // Or by yourself
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)
            ->findOneBy(array('email' => $_email));
        /// End Retrieve user

        // Check if the user exists !
        if(!$user){
            return new Response(json_encode(0));
        }

        /// Start verification
        $encoder = $factory->getEncoder($user);
        $salt = $user->getSalt();

        if(!$encoder->isPasswordValid($user->getPassword(), $_password, $salt)) {
            return new Response(json_encode(0));
        }
        /// End Verification

        // The password matches ! then proceed to set the user in session

        //Handle getting or creating the user entity likely with a posted form
        // The third parameter "main" can change according to the name of your firewall in security.yml
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);

        // If the firewall name is not main, then the set value would be instead:
        // $this->get('session')->set('_security_XXXFIREWALLNAMEXXX', serialize($token));
        $this->get('session')->set('_security_main', serialize($token));

        // Fire the login event manually
        $event = new InteractiveLoginEvent($request, $token);
        //$this->eventDispatcher->dispatch(FOSUserEvents::SECURITY_IMPLICIT_LOGIN, $event);

        /*
         * Now the user is authenticated !!!!
         * Do what you need to do now, like render a view, redirect to route etc.
         */
        return new Response(json_encode(1));
    }
}
