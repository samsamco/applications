<?php
namespace App\Service;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TokenChecker
{

    public function __construct()
    {

    }


    public function checkToken($token)
    {
        if($token!="ea6b2efbdd4255a9f1b3bbc6399b58f4")
        {
            return false;
        }
        return true;
    }


}
