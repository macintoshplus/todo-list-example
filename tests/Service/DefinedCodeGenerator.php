<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 10/04/2020 23:02
 */

declare(strict_types=1);

namespace App\Tests\Service;


use App\Security\CodeGeneratorInterface;

class DefinedCodeGenerator implements CodeGeneratorInterface
{

    public function generate(): string
    {
        return '1234';
    }
}
