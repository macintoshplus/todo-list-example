<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 08/06/2020 23:10
 */

declare(strict_types=1);

namespace App\Security\Listener;


use App\Security\AppTwoFactorAuthenticator;
use App\Security\Badge\TwoFactorCredentials;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

final class TwoFactorCredentialSubscriber implements EventSubscriberInterface
{

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => ['checkCredential', 0]
        ];
    }

    public function checkCredential(CheckPassportEvent $event)
    {
        /** @var Passport $passport */
        $passport = $event->getPassport();
        if ($passport->hasBadge(TwoFactorCredentials::class) === false) {
            return;
        }
        $badge = $passport->getBadge(TwoFactorCredentials::class);
        if ($badge->getPassword() === $this->requestStack->getSession()->get(AppTwoFactorAuthenticator::CODE_SESSION_KEY, null)) {
            $badge->markResolved();
            return;
        }

        $this->requestStack->getSession()->set(AppTwoFactorAuthenticator::COUNT_SESSION_KEY,
            $this->requestStack->getSession()->get(AppTwoFactorAuthenticator::COUNT_SESSION_KEY, 0) + 1);

    }
}
