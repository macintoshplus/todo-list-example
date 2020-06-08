<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 08/06/2020 22:47
 */

declare(strict_types=1);

namespace App\Security\Listener;


use App\Security\AppTwoFactorAuthenticator;
use App\Security\Badge\TwoFactorTimeoutBadge;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

final class TwoFactorTimeoutBadgeSubscriber implements EventSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
            CheckPassportEvent::class => ['resolveBadge', 10]
        ];
    }

    public function resolveBadge(CheckPassportEvent $event)
    {
        /** @var Passport $passport */
        $passport = $event->getPassport();
        if (
            $passport->hasBadge(TwoFactorTimeoutBadge::class) &&
            $this->session->get(AppTwoFactorAuthenticator::TIMEOUT_SESSION_KEY) > time()
        ) {
            $passport->getBadge(TwoFactorTimeoutBadge::class)->markResolved();

        }
    }
}
