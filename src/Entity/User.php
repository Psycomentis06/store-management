<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $username;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    private ArrayCollection $roles;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\OneToOne(targetEntity: UserMetadata::class, cascade: ['persist', 'remove'])]
    private $metadata;

    #[ORM\ManyToOne(targetEntity: UserState::class, inversedBy: 'users')]
    private $state;

    #[ORM\Column(type: 'string', length: 100)]
    private $email;

    #[Pure] public function __construct()
    {
        $this->roles = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @see UserInterface
     * Modified: instead of using JSON object we will use an Entity 'Role' for roles
     */
    public function getRoles(): array
    {
        // guarantee every user at least has ROLE_USER
        $this->roles[] = "ROLE_USER";

        return array_unique((array)$this->roles);
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }
        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->roles->removeElement($role);
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getMetadata(): ?UserMetadata
    {
        return $this->metadata;
    }

    public function setMetadata(?UserMetadata $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getState(): ?UserState
    {
        return $this->state;
    }

    public function setState(?UserState $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
