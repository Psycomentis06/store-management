<?php

namespace App\Entity;

use App\_Interface\SearchableEntityInterface;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, SearchableEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $username;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    private Collection $roles;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\OneToOne(targetEntity: UserMetadata::class, cascade: ['persist', 'remove'])]
    private UserMetadata $metadata;

    #[ORM\ManyToOne(targetEntity: UserState::class, inversedBy: 'users')]
    private UserState $state;

    #[ORM\Column(type: 'string', length: 100)]
    private string $email;

    #[ORM\ManyToOne(targetEntity: DigitalPurchase::class, inversedBy: 'agent')]
    private DigitalPurchase $digitalPurchase;

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
     * Return Array of roles as array of strings for Symfony Internals
     */
    public function getRoles(): array
    {
        $res = [];
        foreach ($this->roles->toArray() as $role) {
            $res[] = $role->getRole();
        }
        return array_unique($res);
    }

    public function getRolesObj(): ArrayCollection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
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

    public function __toString(): string
    {
        return $this->username;
    }

    public function getDigitalPurchase(): ?DigitalPurchase
    {
        return $this->digitalPurchase;
    }

    public function setDigitalPurchase(?DigitalPurchase $digitalPurchase): self
    {
        $this->digitalPurchase = $digitalPurchase;

        return $this;
    }

    public static function getDefaultSearchFieldName(): string
    {
        return 'username';
    }

    public function getSearchCardTitle(): string
    {
        return $this->username;
    }

    public function getSearchCardBody(): string
    {
        return '';
    }

    public function getSearchCardImage(): ?string
    {
        return null;
    }
}
