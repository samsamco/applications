<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


use FOS\UserBundle\Controller\SecurityController as BaseController;

class LoginController extends BaseController
{
    public function index(Request $request)
    {
        return parent::loginAction($request); // TODO: Change the autogenerated stub
    }
}
