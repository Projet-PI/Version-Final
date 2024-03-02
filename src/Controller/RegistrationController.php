<?php

namespace App\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use PHPMailer\PHPMailer\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
//use PHPMailer\PHPMailer\Exception;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @throws Exception
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('Password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

           // $user = $this->getUser();

            // require '/path/to/PHPMailer/src/Exception.php';
            // require '/path/to/PHPMailer/src/PHPMailer.php';
            // require '/path/to/PHPMailer/src/SMTP.php';

            $mail = new \PHPMailer\PHPMailer\PHPMailer(true); // Make sure to have the correct namespace


            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com';
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->Username = 'novaassurance@outlook.com'; // Your Outlook email address
                $mail->Password = 'Nova123!'; // Your Outlook password
                // Sender and recipient settings
                $mail->setFrom('novaassurance@outlook.com', 'Nova Assurance');
                $nomUser = $user->getPrenom(); // Use the $user object
                $emailUser = $user->getEmail(); // Use the $user object
                $mail->addAddress($emailUser);
                $mail->isHTML(true);
                $mail->Subject = 'PRODUCT VERIFIED !';
                $mail->Body = "Cher $nomUser , <br> Votre compte a ete créer avec succées <br>
";
                $mail->AltBody = "hi";
                $mail->send();
                $this->addFlash('success', 'Verification email sent. Please check your inbox.');
            } catch (\PHPMailer\PHPMailer\Exception $e) {
                $this->addFlash('error', 'Mailer Error: ' . $e->getMessage());
                // Log the error message
                // error_log('Mailer Error: ' . $e->getMessage());
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('');
    }
}
