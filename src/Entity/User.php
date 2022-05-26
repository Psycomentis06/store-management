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

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DigitalPurchase::class)]
    private $digitalPurchases;

    #[Pure] public function __construct()
    {
        $this->roles = new ArrayCollection([]);
        $this->digitalPurchases = new ArrayCollection();
    }

    public static function getDefaultSearchFieldName(): string
    {
        return 'username';
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

    public function getPermissions(): array
    {
        $res = [];
        $roles = $this->getRolesObj();
        foreach ($roles->getKeys() as $key) {
            $role = $roles->get($key);
            if ($role instanceof Role) {
                $permissions = $role->getPermissions();
                foreach ($permissions->getKeys() as $pkey) {
                    $permission = $permissions->get($pkey);
                    if ($permission instanceof Permission) {
                        $res[] = $permission->getPermission();
                    }
                }
            }
        }
        return $res;
    }

    public function getRolesObj(): Collection
    {
        return $this->roles;
    }

    /**
     * @return Collection<int, DigitalPurchase>
     */
    public function getDigitalPurchases(): Collection
    {
        return $this->digitalPurchases;
    }

    public function addDigitalPurchase(DigitalPurchase $digitalPurchase): self
    {
        if (!$this->digitalPurchases->contains($digitalPurchase)) {
            $this->digitalPurchases[] = $digitalPurchase;
            $digitalPurchase->setUser($this);
        }

        return $this;
    }

    public function removeDigitalPurchase(DigitalPurchase $digitalPurchase): self
    {
        if ($this->digitalPurchases->removeElement($digitalPurchase)) {
            // set the owning side to null (unless already changed)
            if ($digitalPurchase->getUser() === $this) {
                $digitalPurchase->setUser(null);
            }
        }

        return $this;
    }
}
