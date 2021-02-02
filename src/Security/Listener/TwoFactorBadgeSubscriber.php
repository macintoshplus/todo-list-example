<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 08/06/2020 22:47
 */

declare(strict_types=1);

namespace App\Security\Listener;

use App\Security\Badge\TwoFactorBadge;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

final class TwoFactorBadgeSubscriber implements EventSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function resolveBadge(CheckPassportEvent $event)
    {
        /** @var Passport $passport */
        $passport = $event->getPassport();

        // Here I can check if the user has the two factor authentication enabled
        if ($passport->hasBadge(TwoFactorBadge::class) && $passport->hasBadge(PasswordCredentials::class) && $passport->getBadge(PasswordCredentials::class)->isResolved()) {
            $this->session->set('need_auth_two', true);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            CheckPassportEvent::class => ['resolveBadge', -10],
        ];
    }
}
