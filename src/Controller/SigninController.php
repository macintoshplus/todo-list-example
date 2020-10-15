<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 15/10/2020 20:32
 */

declare(strict_types=1);

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Twig\Environment;

/**
 * @Route("/signin", name="app_registration_")
 */
class SigninController
{
    /**
     * @Route("/registration", name="signin", methods={"GET","POST"})
     */
    public function register(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        EncoderFactoryInterface $encoderFactory
    ) {
        $user = new User(Uuid::uuid4()->toString());
        $form = $formFactory->create(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $encoderFactory->getEncoder($user);
            $user->setPassword($encoder->encodePassword($user->getPassword(), null));
            $entityManager->persist($user);
            $entityManager->flush();

            return new RedirectResponse($urlGenerator->generate('app_login'));
        }
        return new Response($twig->render('registration/register.html.twig', ['form' => $form->createView()]));
    }
}
