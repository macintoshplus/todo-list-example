<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 08/06/2020 22:47
 */

declare(strict_types=1);

namespace App\Security\Listener;


use App\Security\Badge\TwoFactorBadge;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

final class TwoFactorBadgeSubscriber implements EventSubscriberInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function resolveBadge(CheckPassportEvent $event)
    {
        /** @var Passport $passport */
        $passport = $event->getPassport();

        // Here I can check if the user has the two factor authentication enabled
        if ($passport->hasBadge(TwoFactorBadge::class) && $passport->hasBadge(PasswordCredentials::class) && $passport->getBadge(PasswordCredentials::class)->isResolved()) {
            $this->requestStack->getSession()->set('need_auth_two', true);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => ['resolveBadge', -10]
        ];
    }
}
