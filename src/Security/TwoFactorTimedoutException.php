<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 09/04/2020 22:42
 */

declare(strict_types=1);

namespace App\Security;


use Symfony\Component\Security\Core\Exception\AuthenticationException;

class TwoFactorTimedoutException extends AuthenticationException
{
}
