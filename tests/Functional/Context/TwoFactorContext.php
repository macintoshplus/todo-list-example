<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 10/04/2020 22:12
 */

declare(strict_types=1);

namespace App\Tests\Functional\Context;

use App\Tests\Functional\Page\TwoFactorPage;
use Behat\Behat\Context\Context;

class TwoFactorContext implements Context
{
    /**
     * @var TwoFactorPage
     */
    private $twoFactorPage;

    public function __construct(TwoFactorPage $twoFactorPage)
    {
        $this->twoFactorPage = $twoFactorPage;
    }

    /**
     * @When je saisi le code de double authentification
     */
    public function saisieDoubleAuth()
    {
        $this->twoFactorPage->sendCode('1234');
    }

    /**
     * @Then je dois être sur la page de double authentification
     */
    public function verifPageTwoFactor()
    {
        $this->twoFactorPage->verify();
    }
}
