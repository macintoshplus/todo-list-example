<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 10/04/2020 23:00
 */

declare(strict_types=1);

namespace App\Security;

interface CodeGeneratorInterface
{
    public function generate(): string;
}
