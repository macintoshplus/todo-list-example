<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 08/06/2020 23:06
 */

declare(strict_types=1);

namespace App\Security\Badge;


use Symfony\Component\Security\Core\Exception\LogicException;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CredentialsInterface;

class TwoFactorCredentials implements CredentialsInterface
{
    private $password;
    private $resolved = false;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function getPassword(): string
    {
        if (null === $this->password) {
            throw new LogicException('The credentials are erased as another listener already verified these credentials.');
        }

        return $this->password;
    }

    public function markResolved(): void
    {
        $this->resolved = true;
        $this->password = null;
    }

    public function isResolved(): bool
    {
        return $this->resolved;
    }
}
