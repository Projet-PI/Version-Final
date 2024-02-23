<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
//use App\Form\UsermodifyType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;




class UserController extends AbstractController
{

    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    #[Route('/menu', name: 'app_menu')]
    public function indexM(UserRepository $fournisseurRepository): Response
    {
        return $this->render('base.html.twig');
    }
    #[Route('/front', name: 'app_front')]
    public function index(UserRepository $fournisseurRepository): Response
    {
        return $this->render('basefront.html.twig');
    }
    #[Route('/newuser', name: 'app_new_user')]
    public function fnew1(Request $request , EntityManagerInterface $entityManagerInterface)
    {
        $author = new User();

        $form=$this->createForm(UserType::class,$author);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $author=$form->getData();

            $password = $author->getPassword();
            $hashedPassword1 = $this->hasher->hashPassword(
                $author,
                $password
            );
            $author->setPassword($hashedPassword1);
            $entityManagerInterface->persist($author);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_user_back');
        }
        return $this->render('user/ajoutBack.html.twig',['form'=> $form->createView(),]);
        // dump($author);
        // die();
        //
    }


    #[Route('/user/fnew', name: 'app_user_fnew')]
    public function fnew(Request $request , EntityManagerInterface $entityManagerInterface)
    {
        $author = new User();

        $form=$this->createForm(UserType::class,$author);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $author=$form->getData();

            $password = $author->getPassword();
            $hashedPassword1 = $this->hasher->hashPassword(
                $author,
                $password
            );
            $author->setPassword($hashedPassword1);
            $entityManagerInterface->persist($author);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('user/index.html.twig',['form'=> $form->createView(),]);
        // dump($author);
        // die();
        //
    }



    #[Route('/profile/edit', name: 'app_user_profile_edit')]
    public function editProfile(Request $request ): Response
    {
        $author = $this->getUser(); // Retrieve the currently logged-in user

        // Create a form to edit user information
        $form = $this->createForm(UserType::class, $author);
        $form->handleRequest($request);
        $password = $author->getPassword();


        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the changes to the database
            $entityManager = $this->getDoctrine()->getManager();
            $hashedPassword1 = $this->hasher->hashPassword(
                $author,
                $password
            );
            $author->setPassword($hashedPassword1);
            $entityManager->flush();

            // Redirect the user to another page after editing
            return $this->redirectToRoute('homeOn'); // Change to appropriate route
        }

        // Render the profile edit template
        return $this->render('user/edit_profile.html.twig', [
            'user' => $author,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/user/back', name: 'app_user_back')]
    public function index_back(UserRepository $fournisseurRepository): Response
    {
        $authors=$fournisseurRepository->findAll();

        return $this->render('user/show.html.twig', array(
            'authors' => $authors,

        ));
    }

    #[Route('/user/delete/{id}', name: 'app_user_back_delete')]
    public function delete($id ,EntityManagerInterface $entityManagerInterface , UserRepository $authorRepository)
    {
        $author = $authorRepository->find($id);
        $entityManagerInterface->remove($author);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('app_user_back');
        dd($author);

    }



}
