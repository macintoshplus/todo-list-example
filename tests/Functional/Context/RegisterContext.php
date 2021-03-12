<?php
/**
 * @copyright Macintoshplus (c) 2021
 * Added by : Macintoshplus at 12/03/2021 23:05
 */

declare(strict_types=1);

namespace App\Tests\Functional\Context;

use App\Tests\Functional\Page\RegisterPage;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

class RegisterContext implements Context
{
    /**
     * @var RegisterPage
     */
    private $registerPage;

    public function __construct(RegisterPage $registerPage)
    {
        $this->registerPage = $registerPage;
    }

    /**
     * @Then je dois être sur la page d'enregistrement d'un utilisateur
     */
    public function jeDoisEtreSurLaPageDunUtilisateur()
    {
        $this->registerPage->verify();
    }

    /**
     * @When /^je saisi l'adresse courriel "([^"]+)" avec le mot de passe "([^"]+)" et que je valide$/
     */
    public function jeSaisiLAdresseCourrielAvecLeMotDePasseEtQueJeValide(string $email, string $password)
    {
        $this->registerPage->fillFormAndValid($email, $password);
    }

    /**
     * @When /^je dois voir l'erreur "([^"]+)" pour le mot de passe$/
     */
    public function jeDoisVoirLErreurPourLeMotDePasse(string $error)
    {
        $this->registerPage->verify();
        $this->registerPage->passwordFieldHasThisError($error);
    }

    /**
     * @When /^je dois voir l'erreur "([^"]*)" pour le courriel$/
     */
    public function jeDoisVoirLErreurPourLeCourriel(string $error)
    {
        $this->registerPage->verify();
        $this->registerPage->emailFieldHasThisError($error);
    }
}
