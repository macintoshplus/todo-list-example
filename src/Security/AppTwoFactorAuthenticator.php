<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 09/04/2020 22:19
 */

declare(strict_types=1);

namespace App\Security;

use App\Repository\UserRepository;
use App\Security\Badge\TwoFactorCredentials;
use App\Security\Badge\TwoFactorMaxAttemptBadge;
use App\Security\Badge\TwoFactorTimeoutBadge;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

final class AppTwoFactorAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_two_factor';

    public const USER_SESSION_KEY = 'two_auth_user';
    public const CODE_SESSION_KEY = 'two_auth_code';
    public const TIMEOUT_SESSION_KEY = 'two_auth_timeout';
    public const COUNT_SESSION_KEY = 'two_auth_count';

    private $urlGenerator;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        UserRepository $userRepository,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request): bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $request->getSession()->remove('need_auth_two');
        $request->getSession()->remove(self::USER_SESSION_KEY);
        $request->getSession()->remove(self::CODE_SESSION_KEY);
        $request->getSession()->remove(self::TIMEOUT_SESSION_KEY);
        $request->getSession()->remove(self::COUNT_SESSION_KEY);

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse('/');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->getSession()->get(self::USER_SESSION_KEY);
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $email
        );
        $request->getSession()->set(
            AppTwoFactorAuthenticator::USER_SESSION_KEY,
            $email
        );

        return new Passport(new UserBadge($email, function($email) {
            $user =  $this->userRepository->findOneBy(['email' => $email]);
            if (!$user) {
                throw new UserNotFoundException();
            }
            return $user;
        }), new TwoFactorCredentials($request->get('password')), [
            // and CSRF protection using a "csrf_token" field
            new CsrfTokenBadge('authenticate', $request->get('_csrf_token')),
            new TwoFactorTimeoutBadge(),
            new TwoFactorMaxAttemptBadge(),

        ]);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
