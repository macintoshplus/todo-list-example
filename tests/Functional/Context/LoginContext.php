<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 10/04/2020 21:51
 */

declare(strict_types=1);

namespace App\Tests\Functional\Context;

use App\Tests\Functional\Page\LoginPage;
use Behat\Behat\Context\Context;

class LoginContext implements Context
{
    /**
     * @var LoginPage
     */
    private $loginPage;

    public function __construct(LoginPage $loginPage)
    {
        $this->loginPage = $loginPage;
    }

    /**
     * @Given je suis sur la page de connexion
     */
    public function jeSuisSurLaPageDeConnexion()
    {
        $this->loginPage->open();
    }

    /**
     * @When /^je me connecte en tant que "([^"]+)" avec le mot de passe "([^"]+)"$/
     *
     * @param mixed $email
     * @param mixed $password
     */
    public function connexion($email, $password)
    {
        $this->loginPage->login($email, $password);
    }
}
