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
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var null|int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id;

    /**
     * @var null|string
     * @ORM\Column(nullable=false)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var null|string
     * @ORM\Column(nullable=false)
     * @Assert\NotBlank()
     * @Assert\NotCompromisedPassword()
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TodoList", mappedBy="user", orphanRemoval=true)
     */
    private $todoLists;

    public function __construct()
    {
        $this->todoLists = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    public function getUserIdentifier(): string {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|TodoList[]
     */
    public function getTodoLists(): Collection
    {
        return $this->todoLists;
    }

    public function addTodoList(TodoList $todoList): self
    {
        if (!$this->todoLists->contains($todoList)) {
            $this->todoLists[] = $todoList;
            $todoList->setUser($this);
        }

        return $this;
    }

    public function removeTodoList(TodoList $todoList): self
    {
        if ($this->todoLists->contains($todoList)) {
            $this->todoLists->removeElement($todoList);
            // set the owning side to null (unless already changed)
            if ($todoList->getUser() === $this) {
                $todoList->setUser(null);
            }
        }

        return $this;
    }
}
