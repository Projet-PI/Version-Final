<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Form\UserModifyType;
use Random\RandomException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


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
        $form = $this->createForm(UserModifyType::class, $author);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the changes to the database
            $entityManager = $this->getDoctrine()->getManager();


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
    public function index_back(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $searchTerm = $request->query->get('searchTerm', '');

            // Assuming you create a method 'findBySearchTerm' in your UserRepository
            $queryBuilder = $userRepository->findBySearchTerm($searchTerm);

            $authors = $paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                4
            );
            $authorsArray = [];
            foreach ($authors as $author) {
                // Customize this array as needed
                $authorsArray[] = [
                    'id' => $author->getId(),
                    'nom'=> $author->getNom(),
                    // other fields you want to return
                ];
            }

            return new JsonResponse([
                'content' => $this->renderView('user/_authors_list.html.twig', ['authors' => $authors])
            ]);
        }

        $authors = $paginator->paginate(
            $userRepository->findAll(), // Replace with a query that selects all users or a filtered subset
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('user/show.html.twig', [
            'authors' => $authors,
        ]);
    }
// src/Controller/UserController.php

    /**
     * @throws TransportExceptionInterface
     * @throws RandomException
     */
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, UrlGeneratorInterface $urlGenerator): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                $resetToken = bin2hex(random_bytes(32));
                $user->setResetToken($resetToken);
                $user->setResetTokenExpiration(new \DateTime('+1 hour'));
                $entityManager->flush();

                $resetUrl = $urlGenerator->generate('app_reset_password', ['token' => $resetToken], UrlGeneratorInterface::ABSOLUTE_URL);

                $email = (new TemplatedEmail())
                    ->from('novaassurance@oulook.com')
                    ->to($user->getEmail())
                    ->subject('Your password reset request')
                    ->htmlTemplate('emails/reset_password.html.twig')
                    ->context([
                        'resetUrl' => $resetUrl,
                    ]);

                $mailer->send($email);

                $this->addFlash('success', 'A password reset link has been sent if the email is registered in our system.');
                return $this->redirectToRoute('app_login');
            }

            // Consider showing a generic message whether the user was found or not
            // to prevent email enumeration attacks
            $this->addFlash('success', 'A password reset link has been sent if the email is registered in our system.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/email.html.twig', [
            'form' => $form->createView(),
        ]);
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
