<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 10/04/2020 21:53
 */

declare(strict_types=1);

namespace App\Tests\Functional\Setup;


use App\Entity\User;
use Behat\Behat\Context\Context;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserContext implements Context
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    public function __construct(ObjectManager $objectManager, EncoderFactoryInterface $encoderFactory)
    {
        $this->objectManager = $objectManager;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @Given the user :email is registred with the password :password
     * @Given /^l'utilisateur "([^"]+)" est enregsitré avec le mot de passe "([^"]+)"$/
     */
    public function registerUser($email, $password)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->encoderFactory->getEncoder($user)->encodePassword($password, null));
        $this->objectManager->persist($user);
        $this->objectManager->flush();
    }

}
