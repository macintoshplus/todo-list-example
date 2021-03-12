<?php
/**
 * @copyright Macintoshplus (c) 2021
 * Added by : Macintoshplus at 12/03/2021 23:06
 */

declare(strict_types=1);

namespace App\Tests\Functional\Page;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Webmozart\Assert\Assert;

class RegisterPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'app_registration_signin';
    }

    public function fillFormAndValid(string $email, string $password)
    {
        $this->getDocument()->fillField('user_email', $email);
        $this->getDocument()->fillField('user_password_first', $password);
        $this->getDocument()->fillField('user_password_second', $password);
        $this->getDocument()->find('css', 'button[type=submit].btn-primary')->press();
    }

    public function passwordFieldHasThisError(string $error)
    {
        $passwordLabel = $this->getDocument()->find('css', 'label[for=user_password_first]');
        if ($passwordLabel === null) {
            throw new \Exception("Unable to locate the password field");
        }
        Assert::contains($passwordLabel->getText(), $error);
    }

    public function emailFieldHasThisError(string $error)
    {

        $passwordLabel = $this->getDocument()->find('css', 'label[for=user_email]');
        if ($passwordLabel === null) {
            throw new \Exception("Unable to locate the email field");
        }
        Assert::contains($passwordLabel->getText(), $error);
    }
}
