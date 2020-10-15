<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 15/10/2020 21:58
 */

declare(strict_types=1);

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class SecurityKeyRegistrationController
{
    /**
     * @Route(path="/user/key/register", name="app_user_key_register")
     */
    public function register(Environment $twig, TokenStorageInterface $tokenStorage)
    {
        return new Response($twig->render('user/key/register.html.twig', ['user'=>$tokenStorage->getToken()->getUser()]));
    }
    /**
     * @Route(path="/user/key/register-success", name="app_user_key_register_ok")
     */
    public function registerOk(Environment $twig)
    {
        return new Response($twig->render('user/key/register_ok.html.twig'));
    }
}
