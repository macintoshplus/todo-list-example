<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 10/04/2020 22:22
 */

declare(strict_types=1);

namespace App\Tests\Functional\Context\TodoList;


use App\Tests\Functional\Page\TodoList\IndexPage;
use Behat\Behat\Context\Context;

class IndexContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    public function __construct(IndexPage $indexPage)
    {
        $this->indexPage = $indexPage;
    }

    /**
     * @Then je dois être sur la liste des todo listes
     */
    public function jeDoisEtreSurLaListeDesTodoListes()
    {
        $this->indexPage->verify();
    }
}
