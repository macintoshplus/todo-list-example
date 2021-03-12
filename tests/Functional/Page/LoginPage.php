<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 10/04/2020 21:51
 */

declare(strict_types=1);

namespace App\Tests\Functional\Page;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class LoginPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'app_login';
    }

    public function login($user, $password)
    {
        $this->open();
        $this->getDocument()->fillField('email', $user);
        $this->getDocument()->fillField('password', $password);
        $this->getDocument()->find('css', 'button.btn-primary[type=submit]')->press();
    }
}
