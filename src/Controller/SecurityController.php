<?php

// src/Controller/SecurityController.php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $helper)
    {
        return $this->render('Security/login.html.twig', [
            // dernier username saisi (si il y en a un)
            'last_username' => $helper->getLastUsername(),
            // La derniere erreur de connexion (si il y en a une)
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * La route pour se deconnecter.
     *
     * Mais celle ci ne doit jamais être executé car symfony l'interceptera avant.
     *
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            // Par defaut l'utilisateur aura toujours le rôle ROLE_USER
            $user->setRoles(['ROLE_USER']);

            // Date
            $datetime = new \Datetime("now");
            $datetime->modify('+ 2 hour');
            $user->setUserDateInscription($datetime);

            // On enregistre l'utilisateur dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'Security/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/mdp", name="mdp")
     */
    public function mdp(Request $request, \Swift_Mailer $mailer, UserPasswordEncoderInterface $passwordEncoder)
    {
        $mail = $request->request->get('mail');
        $message= 0;
        if ($mail != null) {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $mail]);
            if ($user != null) {
                $chaine= 'azertyuiopqsdfghjklmwxcvbn123456789';
                $nb_lettres = strlen($chaine) - 1;
                $nMdp = '';
                for($i=0; $i < 8; $i++)
                {
                    $pos = mt_rand(0, $nb_lettres);
                    $car = $chaine[$pos];
                    $nMdp .= $car;
                }
                $password = $passwordEncoder->encodePassword($user, $nMdp);
                $user->setPassword($password);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $email = (new \Swift_Message('Mot de passe oublié'))
                    ->setFrom('sgosse10@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                        // templates/Mail/mdpMail.html.twig
                            'Mail/mdpMail.html.twig',
                            array('name' => $user->getUsername(), 'nMdp' => $nMdp)
                        ),
                        'text/html'
                    )
                ;
                $mailer->send($email);

                $message = 2;
            } else {
                $message = 1;
            }
        }
        return $this->render('Security/mdp.html.twig', ['message' => $message]);
    }
}

?>