<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 10/04/2020 22:24
 */

declare(strict_types=1);

namespace App\Tests\Functional\Page\TodoList;


use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class IndexPage extends SymfonyPage
{

    public function getRouteName(): string
    {
        return 'todo_list_index';
    }
}
