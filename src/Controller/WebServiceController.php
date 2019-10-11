<?php

namespace App\Controller;

use App\Entity\Bilan;
use App\Entity\Gestionnaire;
use App\Entity\Reservation;
use App\Entity\Scpi;
use App\Entity\User;
use App\Service\SendSms;
use App\Service\TokenChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;

class WebServiceController extends AbstractController
{
    /**
     * @Route("/reserver", name="reserver")
     */
    public function reserver(Request $request)
    {

        $checker = new TokenChecker();

        $token = $request->get('token');

        $vtoken = $checker->checkToken($token);

        if($vtoken) {

            $montant = $request->get('montant');
            $nombre_part = $request->get('nombre_part');
            $scpi = $request->get('scpi');
            $user = $request->get('user');
            $date_reservation = new \DateTime(date('Y-m-d h:m:i'));
            $res = new Reservation();

            $em = $this->getDoctrine()->getManager();

            if (is_null($montant) || !isset($montant))
                return new Response(json_encode(0));

            if (!is_numeric($montant) || !is_double(doubleval($montant)))
                return new Response(json_encode(0));

            if (is_null($nombre_part) || !isset($nombre_part))
                return new Response(json_encode(0));

            if (!is_numeric($nombre_part))
                return new Response(json_encode(0));

            if (is_null($scpi) || !isset($scpi) || empty($scpi))
                return new Response(json_encode(0));

            if (!is_numeric($scpi))
                return new Response(json_encode(0));




            $scpiF = $this->getDoctrine()->getRepository(Scpi::class)
                ->findOneBy(array('id' => $scpi));

            if (is_null($scpiF))
                return new Response(json_encode(0));

            $compteF = $this->getDoctrine()->getRepository(User::class)
                ->findOneBy(array('id' => $user));

            if (is_null($compteF))
                return new Response(json_encode(0));


            $res->setMontant($montant)
                ->setNombrePart($nombre_part)
                ->setScpi($scpiF)
                ->setUser($compteF)
                ->setDateRes($date_reservation);

            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($res);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new Response(json_encode($res->getId()));
        }
        else
        {
            return new Response(json_encode(-1));
        }

    }

    /**
     * @Route("/getscpi", name="getscpi")
     */
    public  function  getScpi(Request $request)
    {
        $checker = new TokenChecker();

        $token = $request->get('token');

        $vtoken = $checker->checkToken($token);

        if($vtoken)
        {
            $idscpi = $request->get('id');

            $scpi = $this->getDoctrine()->getRepository(Scpi::class)
                ->findOneBy(array('id' => $idscpi));

            if (is_null($scpi))
                return new Response(json_encode(0));


            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $scpiS = $serializer->serialize($scpi, 'json');
            $scpiS = json_decode($scpiS, true);

            return new Response(json_encode($scpiS));
        }
        else
        {
            return new Response(json_encode(-1));
        }
    }


    /**
     * @Route("/getgestionnaire", name="getgestionnaire")
     */
    public  function  getGestionnaire(Request $request)
    {
        $checker = new TokenChecker();

        $token = $request->get('token');

        $vtoken = $checker->checkToken($token);

        if($vtoken)
        {
            $idgestionnaire = $request->get('id');

            $gestionnaire = $this->getDoctrine()->getRepository(Gestionnaire::class)
                ->findOneBy(array('id' => $idgestionnaire));

            if (is_null($gestionnaire))
                return new Response(json_encode(0));


            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $gestionnaireS = $serializer->serialize($gestionnaire, 'json');
            $gestionnaireS = json_decode($gestionnaireS, true);

            return new Response(json_encode($gestionnaireS));

        }
        else
        {
            return new Response(json_encode(-1));
        }
    }

    /**
     * @Route("/getbilan", name="getbilan")
     */
    public  function  getBilan(Request $request)
    {
        $checker = new TokenChecker();

        $token = $request->get('token');

        $vtoken = $checker->checkToken($token);

        if($vtoken)
        {
            $idbilan = $request->get('id');

            $bilan = $this->getDoctrine()->getRepository(Bilan::class)
                ->findOneBy(array('id' => $idbilan));

            if (is_null($bilan))
                return new Response(json_encode(0));


            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $bilanS = $serializer->serialize($bilan, 'json');
            $bilanS = json_decode($bilanS, true);

            return new Response(json_encode($bilanS));

        }
        else
        {
            return new Response(json_encode(-1));
        }
    }


    /**
     * @Route("/getreservation", name="getreservation")
     */
    public  function  getReservation(Request $request)
    {
        $checker = new TokenChecker();

        $token = $request->get('token');

        $vtoken = $checker->checkToken($token);

        if($vtoken)
        {
            $idreservation = $request->get('id');

            $res = $this->getDoctrine()->getRepository(Reservation::class)
                ->findOneBy(array('id' => $idreservation));

            if (is_null($res))
                return new Response(json_encode(0));


            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $resS = $serializer->serialize($res, 'json');
            $resS = json_decode($resS, true);

            return new Response(json_encode($resS));

        }
        else
        {
            return new Response(json_encode(-1));
        }
    }


    /**
     * @Route("/getallreservations", name="getallreservations")
     */
    public  function  getAllReservations(Request $request)
    {
        $checker = new TokenChecker();

        $token = $request->get('token');

        $vtoken = $checker->checkToken($token);

        if($vtoken)
        {
            $idcompte = $request->get('id');

            $allres = $this->getDoctrine()->getRepository(Reservation::class)
                ->findBy(array('User' => $idcompte));

            if (is_null($allres))
                return new Response(json_encode(0));


            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $allresS = $serializer->serialize($allres, 'json');
            $allresS = json_decode($allresS, true);

            return new Response(json_encode($allresS));

        }
        else
        {
            return new Response(json_encode(-1));
        }
    }


    /**
     * @Route("/getallscpi", name="getallreservations")
     */
    public  function  getAllScpi(Request $request)
    {

        $checker = new TokenChecker();

        $token = $request->get('token');

        $vtoken = $checker->checkToken($token);

        if($vtoken)
        {

            $allscpi = $this->getDoctrine()->getRepository(Scpi::class)
                ->findAll();

            if (is_null($allscpi))
                return new Response(json_encode(0));


            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $allscpiS = $serializer->serialize($allscpi, 'json');
            $allscpiS = json_decode($allscpiS, true);

            return new Response(json_encode($allscpiS));

        }
        else
        {
            return new Response(json_encode(-1));
        }
    }



    /**
     * @Route("/sendsms", name="sendsmss")
     */
    public function sendSMS(Request $request){

        $code = $request->get('code');
        $phone = $request->get('phone');

        $checker = new TokenChecker();

        $token = $request->get('token');

        $vtoken = $checker->checkToken($token);

        if(!$vtoken)
            return new Response(json_encode(-1));

        $s = new SendSms();
        $s->sendTest('AC8f8df7dcf9a7d34dd748fe8292828e0b', 'e6f4155e6799b7805aaf8b3590eff5d2', $phone, $code);

        return new Response(json_encode(1));

    }
}
