<?php

namespace App\Controller;

use App\Security\AppTwoFactorAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/two_factor", name="app_two_factor")
     */
    public function twoFactor(SessionInterface $session, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($session->get(AppTwoFactorAuthenticator::CODE_SESSION_KEY) === null) {
            $error = null;
            $session->set(AppTwoFactorAuthenticator::CODE_SESSION_KEY, (string) dump(random_int(1000, 9999)));
            $session->set(AppTwoFactorAuthenticator::TIMEOUT_SESSION_KEY, time() + (60 * 5));
            $session->set(AppTwoFactorAuthenticator::COUNT_SESSION_KEY, 1);
        }
        return $this->render('security/two_factor.html.twig', ['error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
