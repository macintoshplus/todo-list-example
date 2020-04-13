<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 10/04/2020 23:01
 */

declare(strict_types=1);

namespace App\Security;

final class RandomCodeGenerator implements CodeGeneratorInterface
{
    public function generate(): string
    {
        return (string) random_int(1000, 9999);
    }
}
