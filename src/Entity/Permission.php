<?php

namespace App\Entity;

use App\_Interface\SearchableEntityInterface;
use App\Repository\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission implements SearchableEntityInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $permission;

    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: 'permissions')]
    private Collection $roles;

    #[ORM\OneToOne(mappedBy: 'permission', targetEntity: Route::class, cascade: ['persist', 'remove'])]
    private Route $route;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private $defaultRole;

    #[Pure] public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPermission(): ?string
    {
        return $this->permission;
    }

    public function setPermission(string $permission): self
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addPermission($this);
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->removeElement($role)) {
            $role->removePermission($this);
        }

        return $this;
    }

    public function getRoute(): ?Route
    {
        return $this->route;
    }

    public function setRoute(?Route $route): self
    {
        // unset the owning side of the relation if necessary
        if ($route === null && $this->route !== null) {
            $this->route->setPermission(null);
        }

        // set the owning side of the relation if necessary
        if ($route !== null && $route->getPermission() !== $this) {
            $route->setPermission($this);
        }

        $this->route = $route;

        return $this;
    }

    public static function getDefaultSearchFieldName(): string
    {
        return 'permission';
    }

    public function getSearchCardTitle(): string
    {
        return $this->permission;
    }

    public function getSearchCardBody(): string
    {
        return '';
    }

    public function getSearchCardImage(): ?string
    {
        return null;
    }

    public function getDefaultRole(): ?string
    {
        return $this->defaultRole;
    }

    public function setDefaultRole(?string $defaultRole): self
    {
        $this->defaultRole = $defaultRole;

        return $this;
    }
}
