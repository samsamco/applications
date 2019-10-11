<?php

namespace App\Controller;

use App\Entity\Scpi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/",name="HomePage")
     * @return Response
     */
    public function home()
    {
        return $this->render('user/index.html.twig');
    }

    /**
     * @Route("/register",name="register")
     * @return Response
     */
    public function register(Request $request)
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            return $this->redirect('/');
        }
        else
        {
            return $this->render('user/login.html.twig', ['type' => "register","plein"=>true]);
        }
    }


    /**
     * @Route("/login",name="login")
     * @return Response
     */
    public function login(Request $request)
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            return $this->redirect('/');
        }
        else
        {
            return $this->render('user/login.html.twig', ['type' => "login","plein"=>true]);
        }

    }

    /**
     * @Route("/reservation",name="reservation")
     * @return Response
     */
    public function reservation(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $scpis = $em->getRepository(Scpi::class)
            ->findAll();

        return $this->render('user/reservation.html.twig', ['scpis' => $scpis]);

    }


    /**
     * @Route("/detailspanier",name="detailspanier")
     * @return Response
     */
    public function detailspanier()
    {
        $em = $this->getDoctrine()->getManager();
        $scpis = $em->getRepository(Scpi::class)
            ->findAll();

        return $this->render('user/panier_details.html.twig',['scpis' => $scpis]);
    }

}
