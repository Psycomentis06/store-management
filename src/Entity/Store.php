<?php

namespace App\Entity;

use App\_Interface\SearchableEntityInterface;
use App\Repository\StoreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: StoreRepository::class)]
class Store implements SearchableEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 20)]
    private string $name;

    #[ORM\Column(type: 'json')]
    private array $address = [];

    #[ORM\OneToMany(mappedBy: 'store', targetEntity: DigitalPurchase::class)]
    private Collection $digitalPurchases;

    #[ORM\OneToOne(inversedBy: 'store', targetEntity: Schedule::class, cascade: ['persist', 'remove'])]
    private Schedule $schedule;

    #[ORM\OneToOne(inversedBy: 'store', targetEntity: Inventory::class, cascade: ['persist', 'remove'])]
    private Inventory $inventory;

    /**
     * @return Inventory
     */
    public function getInventory(): Inventory
    {
        return $this->inventory;
    }

    /**
     * @param Inventory $inventory
     */
    public function setInventory(Inventory $inventory): void
    {
        $this->inventory = $inventory;
    }

    #[Pure] public function __construct()
    {
        $this->digitalPurchases = new ArrayCollection();
    }

    public static function getDefaultSearchFieldName(): string
    {
        return 'name';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?array
    {
        return $this->address;
    }

    public function setAddress(array $address): self
    {
        $this->address = $address;

        return $this;
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
            $digitalPurchase->setStore($this);
        }

        return $this;
    }

    public function removeDigitalPurchase(DigitalPurchase $digitalPurchase): self
    {
        if ($this->digitalPurchases->removeElement($digitalPurchase)) {
            // set the owning side to null (unless already changed)
            if ($digitalPurchase->getStore() === $this) {
                $digitalPurchase->setStore(null);
            }
        }

        return $this;
    }

    public function getSearchCardTitle(): string
    {
        return $this->name;
    }

    public function getSearchCardBody(): string
    {
        return json_encode($this->address);
    }

    public function getSearchCardImage(): ?string
    {
        return null;
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(?Schedule $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
