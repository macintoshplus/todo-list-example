<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 10/04/2020 21:51
 */

declare(strict_types=1);

namespace App\Tests\Functional\Page;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class TwoFactorPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'app_two_factor';
    }

    public function sendCode($code)
    {
        $this->verify();

        $this->getDocument()->fillField('password', $code);
        $this->getDocument()->pressButton('Send code');
    }
}
