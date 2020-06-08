<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 08/06/2020 22:44
 */

declare(strict_types=1);

namespace App\Security\Badge;


use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

final class TwoFactorMaxAttemptBadge implements BadgeInterface
{

    private $resolved = false;

    public function markResolved(): void
    {
        $this->resolved=true;
    }

    public function isResolved(): bool
    {
        return $this->resolved;
    }
}
