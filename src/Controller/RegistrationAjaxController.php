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
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TokenChecker;


class RegistrationAjaxController extends BaseController
{
    private $eventDispatcher;
    private $formFactory;
    private $userManager;
    private $tokenStorage;

    public function __construct(EventDispatcherInterface $eventDispatcher, FactoryInterface $formFactory, UserManagerInterface $userManager, TokenStorageInterface $tokenStorage)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/api_register", name="registera")
     */
    public  function  register(Request $request)
    {
        $checker = new TokenChecker();

        $token = $request->get('token');

        $vtoken = $checker->checkToken($token);

        if (!$vtoken)
            return new Response(json_encode(-1));

        $user = $this->userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return new Response(json_encode(1));
        }

        $nom = $request->get('nom');
        $prenom = $request->get('prenom');
        $username = $request->get('username');
        $email = $request->get('email');
        $password = $request->get('password');
        $telephone = $request->get('tel');
        $adresse = $request->get('adresse');

        $user->setUsername($nom." ".$prenom." ".uniqid());
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setPlainPassword($password);
        $user->setTel($telephone);
        $user->setAdresse($adresse);


        $entityManager = $this->getDoctrine()->getManager();

        $user1 = $entityManager->getRepository(User::class)
            ->findOneBy(array('email'=>$email));

        if($user1)
            return new Response(json_encode(0));


        /*$user1 = $entityManager->getRepository(User::class)
            ->findOneBy(array('username'=>$nom." ".$prenom));

        if($user1)
              return new Response(json_encode(0));*/

        $form = $this->formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        $event = new FormEvent($form, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

        $this->userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('fos_user_registration_confirmed');
            return new Response(json_encode(1));
        }

        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

        return $response;

        $event = new FormEvent($form, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

        if (null !== $response = $event->getResponse()) {
            return new Response(json_encode(1));
        }

        return new Response(json_encode(1));
    }
}
