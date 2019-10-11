<?php

namespace App\Controller;

use App\Entity\Bilan;
use App\Entity\User;
use App\Entity\Gestionnaire;
use App\Entity\Reservation;
use App\Entity\Scpi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            return $this->render('admin/index.html.twig');
        }
        else
        {
            return $this->render('admin/index.html.twig');
        }

    }


    /**
     * @Route("/admin/scpi/edit/{id}", name="admin_editscpi")
     */
    public function editscpi($id,Request $request)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();

        $scpi = $entityManager->getRepository(Scpi::class)
            ->find($id);

        if(!$scpi)
            throw new NotFoundHttpException("Scpi non trouvée");


        $form = $this->createFormBuilder($scpi,[
            'method' => 'POST',
        ])
            ->add('nom', TextType::class)
            ->add('annee', NumberType::class)
            ->add('nature', TextType::class)
            ->add('rendementactuel', NumberType::class)
            ->add('valeurpart', NumberType::class)
            ->add('MyGestionnaire', EntityType::class, [
                'class' => Gestionnaire::class,
                'choice_label' => 'nom',
            ])
            ->add('Bilan', EntityType::class, [
                'class' => Bilan::class,
                'choice_label' => 'url',
            ])
            ->add('logo', FileType::class,['mapped'=>false,'required' => false])
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $logo = $form['logo']->getData();
            if ($logo) {
                $destination = $this->getParameter('kernel.project_dir').'/public/logos';
                $originalFilename = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename="";
                if(!empty(trim($scpi->getLogo())))
                {
                    $newFilename =substr($scpi->getLogo(), 0, strrpos($scpi->getLogo(), ".")).'.'.$logo->guessExtension();
                }
                else
                {
                    $newFilename = uniqid().'.'.$logo->guessExtension();
                }
                $logo->move(
                    $destination,
                    $newFilename
                );

                $scpi->setLogo($newFilename);
            }

            $data = $form->getData();

            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('message', 'scpi été bien enregistré');

            //return $this->redirectToRoute('admin_listscpi');

        }

        return $this->render('admin/scpi/add.html.twig', [
            'form' => $form->createView(),
        ]);

    }



    /**
     * @Route("/admin/scpi/delete/{id}", name="admin_deletescpi")
     */
    public function deletescpi($id,Request $request)
    {
        $token = $request->get('token');


        $entityManager = $this->getDoctrine()->getManager();
        $scpi = $entityManager->getRepository(Scpi::class)->find($id);

        if (!$scpi) {
            throw $this->createNotFoundException(
                'Objet non trouvé '.$id
            );
        }

        $entityManager->remove($scpi);
        $entityManager->flush();

        return $this->redirectToRoute('admin_listscpi');

    }




    /**
     * @Route("/admin/scpi/add", name="admin_addscpi")
     */
    public function ajouterscpi(Request $request)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();
        $scpi = new Scpi();


        $form = $this->createFormBuilder($scpi,[
            'method' => 'POST',
        ])
            ->add('nom', TextType::class)
            ->add('annee', NumberType::class)
            ->add('nature', TextType::class)
            ->add('rendementactuel', NumberType::class)
            ->add('valeurpart', NumberType::class)
            ->add('MyGestionnaire', EntityType::class, [
                'class' => Gestionnaire::class,
                'choice_label' => 'nom',
            ])
            ->add('Bilan', EntityType::class, [
                'class' => Bilan::class,
                'choice_label' => 'url',
            ])
            ->add('logo', FileType::class,['mapped'=>false,'required' => false])
            ->add('coleur', ColorType::class,['mapped'=>false,'required' => false])
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $logo = $form['logo']->getData();
            if ($logo) {
                $destination = $this->getParameter('kernel.project_dir').'/public/logos';
                $originalFilename = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$logo->guessExtension();
                $logo->move(
                    $destination,
                    $newFilename
                );
                $scpi->setLogo($newFilename);
            }

            $color = $form['coleur']->getData();
            if($color)
            {
                $scpi->setColeur($color);
            }

            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $scpi->setUser($user);


            $data = $form->getData();

            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('message', 'scpi été bien enregistré');

            //return $this->redirectToRoute('admin_addscpi');

        }

        return $this->render('admin/scpi/add.html.twig', [
            'form' => $form->createView(),
        ]);

    }



    /**
     * @Route("/admin/scpi/list", name="admin_listscpi")
     */
    public function listscpi(Request $request)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();

        $listscpi = $entityManager->getRepository(Scpi::class)
            ->findAll();


        return $this->render('admin/scpi/list.html.twig', [
            'scpis' => $listscpi,
        ]);
    }



    /**
     * @Route("/admin/gestionnaire/list", name="admin_listgestionnaire")
     */
    public function listgestionnaire(Request $request)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();

        $listgestionnaire = $entityManager->getRepository(Gestionnaire::class)
            ->findAll();


        return $this->render('admin/gestionnaire/list.html.twig', [
            'gestionnaires' => $listgestionnaire,
        ]);
    }



    /**
     * @Route("/admin/gestionnaire/add", name="admin_addgestionnaire")
     */
    public function addgestionnaire(Request $request)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();
        $gest = new Gestionnaire();


        $form = $this->createFormBuilder($gest,[
            'method' => 'POST',
        ])
            ->add('nom', TextType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('message', 'le gestionnaire a été bien enregistré');

        }

        return $this->render('admin/gestionnaire/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/gestionnaire/edit/{id}", name="admin_editgestionnaire")
     */
    public function editgestionnaire(Request $request,$id)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();


        $gest = $entityManager->getRepository(Gestionnaire::class)
            ->find($id);


        $form = $this->createFormBuilder($gest,[
            'method' => 'POST',
        ])
            ->add('nom', TextType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('message', 'le gestionnaire a été bien enregistré');

        }

        return $this->render('admin/gestionnaire/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/admin/gestionnaire/delete/{id}", name="admin_deletegestionnaire")
     */
    public function deletegestionnaire($id,Request $request)
    {
        $token = $request->get('token');


        $entityManager = $this->getDoctrine()->getManager();
        $gestionnaire = $entityManager->getRepository(Gestionnaire::class)->find($id);

        if (!$gestionnaire) {
            throw $this->createNotFoundException(
                'Objet non trouvé '.$id
            );
        }

        $entityManager->remove($gestionnaire);
        $entityManager->flush();

        return $this->redirectToRoute('admin_listgestionnaire');

    }






    /**
     * @Route("/admin/compte/list", name="admin_listcompte")
     */
    public function listcompte(Request $request)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();

        $listcompte = $entityManager->getRepository(User::class)
            ->findAll();


        return $this->render('admin/compte/list.html.twig', [
            'comptes' => $listcompte,
        ]);
    }



    /**
     * @Route("/admin/compte/add", name="admin_addcompte")
     */
    public function addcompte(Request $request)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();
        $compte = new User();


        $form = $this->createFormBuilder($compte,[
            'method' => 'POST',
        ])
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('identifiant', FileType::class,['mapped' => false,'required' => false])
            ->add('avatar', FileType::class,['mapped' => false,'required' => false])
            ->add('tel', TelType::class)
            ->add('adresse', TextType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $identifiant = $form['identifiant']->getData();
            if ($identifiant) {
                $destination = $this->getParameter('kernel.project_dir').'/public/identifiants';
                $originalFilename = pathinfo($identifiant->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$identifiant->guessExtension();
                $identifiant->move(
                    $destination,
                    $newFilename
                );
                $compte->setIdentifiant($newFilename);
            }


            $avatar = $form['avatar']->getData();
            if ($avatar) {
                $destination = $this->getParameter('kernel.project_dir').'/public/avatars';
                $originalFilename = pathinfo($identifiant->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$avatar->guessExtension();
                $avatar->move(
                    $destination,
                    $newFilename
                );
                $compte->setAvatar($newFilename);
            }

            $data = $form->getData();

            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('message', 'Le compte été bien enregistré');

            //return $this->redirectToRoute('admin_addcompte');

        }

        return $this->render('admin/compte/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/compte/edit/{id}", name="admin_editcompte")
     */
    public function editcompte(Request $request,$id)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();


        $compte = $entityManager->getRepository(User::class)
            ->find($id);


        $form = $this->createFormBuilder($compte,[
            'method' => 'POST',
        ])
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('identifiant', FileType::class,['mapped'=>false,'required' => false])
            ->add('avatar', FileType::class,['mapped'=>false,'required' => false])
            ->add('tel', TelType::class)
            ->add('adresse', TextType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $identifiant = $form['identifiant']->getData();

            if ($identifiant) {
                $destination = $this->getParameter('kernel.project_dir').'/public/identifiants';
                $originalFilename = pathinfo($identifiant->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename="";
                if(!empty(trim($compte->getIdentifiant())))
                {
                    $newFilename =substr($compte->getIdentifiant(), 0, strrpos($compte->getIdentifiant(), ".")).'.'.$identifiant->guessExtension();
                }
                else
                {
                    $newFilename = uniqid().'.'.$identifiant->guessExtension();
                }
                $identifiant->move(
                    $destination,
                    $newFilename
                );

                $compte->setIdentifiant($newFilename);
            }

            $avatar = $form['avatar']->getData();

            if ($avatar) {
                $destination = $this->getParameter('kernel.project_dir').'/public/avatars';
                $originalFilename = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename="";
                if(!empty(trim($compte->getavatar())))
                {
                    $newFilename =substr($compte->getavatar(), 0, strrpos($compte->getavatar(), ".")).'.'.$avatar->guessExtension();
                }
                else
                {
                    $newFilename = uniqid().'.'.$avatar->guessExtension();
                }
                $identifiant->move(
                    $destination,
                    $newFilename
                );

                $compte->setAvatar($newFilename);
            }

            $data = $form->getData();

            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute('admin_listcompte');

        }

        return $this->render('admin/compte/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/admin/compte/delete/{id}", name="admin_deletecompte")
     */
    public function deletecompte($id,Request $request)
    {
        $token = $request->get('token');


        $entityManager = $this->getDoctrine()->getManager();
        $compte = $entityManager->getRepository(User::class)->find($id);

        if (!$compte) {
            throw $this->createNotFoundException(
                'Objet non trouvé '.$id
            );
        }

        $entityManager->remove($compte);
        $entityManager->flush();

        return $this->redirectToRoute('admin_listcompte');

    }







    /**
     * @Route("/admin/bilan/list", name="admin_listbilan")
     */
    public function listbilan(Request $request)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();

        $listbilan = $entityManager->getRepository(Bilan::class)
            ->findAll();


        return $this->render('admin/bilan/list.html.twig', [
            'bilans' => $listbilan,
        ]);
    }



    /**
     * @Route("/admin/bilan/add", name="admin_addbilan")
     */
    public function addbilan(Request $request)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();
        $bilan = new Bilan();


        $form = $this->createFormBuilder($bilan,[
            'method' => 'POST',
        ])
            ->add('date_bilan', DateType::class)
            ->add('url', UrlType::class)
            ->add('url_historique', UrlType::class)
            ->add('save', SubmitType::class)
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /*if(isset($form['date_bilan']) && !empty($form['date_bilan']))
            {
                $bilan->setDateBilan(date('Y-m-d', strtotime($form['date_bilan'])));
            }*/

            $data = $form->getData();

            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('message', 'Le bilan été bien enregistré');

        }

        return $this->render('admin/bilan/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/bilan/edit/{id}", name="admin_editbilan")
     */
    public function editbilan(Request $request,$id)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();


        $bilan = $entityManager->getRepository(Bilan::class)
            ->find($id);


        $form = $this->createFormBuilder($bilan,[
            'method' => 'POST',
        ])
            ->add('date_bilan', DateType::class)
            ->add('url', UrlType::class)
            ->add('url_historique', UrlType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('message', 'Le bilan été bien enregistré');

        }

        return $this->render('admin/bilan/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/admin/bilan/delete/{id}", name="admin_deletebilan")
     */
    public function deletebilan($id,Request $request)
    {
        $token = $request->get('token');


        $entityManager = $this->getDoctrine()->getManager();
        $bilan = $entityManager->getRepository(Bilan::class)->find($id);

        if (!$bilan) {
            throw $this->createNotFoundException(
                'Objet non trouvé '.$id
            );
        }

        $entityManager->remove($bilan);
        $entityManager->flush();

        return $this->redirectToRoute('admin_listbilan');

    }







    /**
     * @Route("/admin/reservation/list", name="admin_listres")
     */
    public function listreservations(Request $request)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();

        $reservations = $entityManager->getRepository(Reservation::class)
            ->findAll();


        return $this->render('admin/reservations/list.html.twig', [
            'reservations' => $reservations,
        ]);
    }



    /**
     * @Route("/admin/reservations/add", name="admin_addreservation")
     */
    public function addreservation(Request $request)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();
        $reservation = new Reservation();


        $form = $this->createFormBuilder($reservation,[
            'method' => 'POST',
        ])
            ->add('date_res', DateType::class)
            ->add('montant', NumberType::class)
            ->add('nombre_part', NumberType::class)
            ->add('Scpi', EntityType::class, [
                'class' => Scpi::class,
                'choice_label' => 'nom',
            ])
            ->add('User', EntityType::class, [
                'class' => User::class,
                'choice_label' => function ($user) {
                    return $user->getNom() . ' ' . $user->getPrenom();
                },
            ])
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('message', 'La réservation a été bien enregistrée');

        }

        return $this->render('admin/reservations/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/reservations/edit/{id}", name="admin_editreservation")
     */
    public function editreservation(Request $request,$id)
    {
        $defaults = ['token' => 'ea6b2efbdd4255a9f1b3bbc6399b58f4'];

        $entityManager = $this->getDoctrine()->getManager();
        $reservation = $entityManager->getRepository(Reservation::class)
            ->find($id);


        $form = $this->createFormBuilder($reservation,[
            'method' => 'POST',
        ])
            ->add('date_res', DateType::class)
            ->add('montant', NumberType::class)
            ->add('nombre_part', NumberType::class)
            ->add('Scpi', EntityType::class, [
                'class' => Scpi::class,
                'choice_label' => 'nom',
            ])
            ->add('Compte', EntityType::class, [
                'class' => Compte::class,
                'choice_label' => 'nom',
            ])
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('message', 'La réservation a été bien enregistrée');

        }

        return $this->render('admin/reservations/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("admin/reservations/delete/{id}", name="admin_deletereservations")
     */
    public function deletereservation($id,Request $request)
    {
        $token = $request->get('token');


        $entityManager = $this->getDoctrine()->getManager();
        $reservation = $entityManager->getRepository(Reservation::class)->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException(
                'Objet non trouvé '.$id
            );
        }

        $entityManager->remove($reservation);
        $entityManager->flush();

        return $this->redirectToRoute('admin_listcompte');

    }


}
