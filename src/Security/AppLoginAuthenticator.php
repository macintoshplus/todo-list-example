<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\UserRepository;
use App\Security\Badge\TwoFactorBadge;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PasswordUpgradeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

final class AppLoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $urlGenerator;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        UserRepository $userRepository,
        UrlGeneratorInterface $urlGenerator,
        SessionInterface $session
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request): ?bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse('/');
    }

    public function authenticate(Request $request): PassportInterface
    {
        $email = $request->get('email');
        $user = $this->userRepository->findOneBy(['email' => $email]);
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $email
        );
        $request->getSession()->set(
            AppTwoFactorAuthenticator::USER_SESSION_KEY,
            $email
        );
        if (!$user) {
            throw new UsernameNotFoundException();
        }

        return new Passport($user, new PasswordCredentials($request->get('password')), [
            // and CSRF protection using a "csrf_token" field
            new CsrfTokenBadge('authenticate', $request->get('_csrf_token')),

            // and add support for upgrading the password hash
            new PasswordUpgradeBadge($request->get('password'), $this->userRepository),
            new TwoFactorBadge(),
        ]);
    }

    protected function getLoginUrl(Request $request): string
    {
        if ($this->session->get('need_auth_two', false) === true) {
            return $this->urlGenerator->generate(AppTwoFactorAuthenticator::LOGIN_ROUTE);
        }

        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
