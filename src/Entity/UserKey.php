<?php
/**
 * @copyright Macintoshplus (c) 2020
 * Added by : Macintoshplus at 09/04/2020 21:32
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Webauthn\PublicKeyCredentialUserEntity;

/**
 * @ORM\Entity()
 */
class UserKey extends PublicKeyCredentialUserEntity implements UserInterface
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="string",length=255, nullable=false)
     */
    protected $id;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles;


    public function __construct(string $id, string $name, string $displayName, ?string $icon = null, array $roles = [])
    {
        parent::__construct($name, $id, $displayName, $icon);
        $this->roles = $roles;
    }

    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return array_unique($this->roles + ['ROLE_USER']);
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}
