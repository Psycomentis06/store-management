<?php

namespace App\Entity;

use App\_Interface\SearchableEntityInterface;
use App\Repository\DigitalPurchaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: DigitalPurchaseRepository::class)]
class DigitalPurchase implements SearchableEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'integer')]
    private int $price;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $expiresOn;

    #[ORM\Column(type: 'smallint')]
    private int $quantity = 1;

    #[ORM\ManyToOne(targetEntity: Store::class, inversedBy: 'digitalPurchases')]
    private Store $store;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'digitalPurchases')]
    private Customer $consumer;

    #TODO change to OneToMany instead & Add production relation
    #[ORM\OneToMany(mappedBy: 'digitalPurchase', targetEntity: User::class)]
    private Collection $agent;

    #[ORM\Column(type: 'json')]
    private array $credentials = [];

    public function __construct()
    {
        $this->agent = new ArrayCollection();
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getExpiresOn(): ?\DateTimeInterface
    {
        return $this->expiresOn;
    }

    public function setExpiresOn(\DateTimeInterface $expiresOn): self
    {
        $this->expiresOn = $expiresOn;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): self
    {
        $this->store = $store;

        return $this;
    }

    public function getConsumer(): ?Customer
    {
        return $this->consumer;
    }

    public function setConsumer(?Customer $consumer): self
    {
        $this->consumer = $consumer;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAgent(): Collection
    {
        return $this->agent;
    }

    public function addAgent(User $agent): self
    {
        if (!$this->agent->contains($agent)) {
            $this->agent[] = $agent;
            $agent->setDigitalPurchase($this);
        }

        return $this;
    }

    public function removeAgent(User $agent): self
    {
        if ($this->agent->removeElement($agent)) {
            // set the owning side to null (unless already changed)
            if ($agent->getDigitalPurchase() === $this) {
                $agent->setDigitalPurchase(null);
            }
        }

        return $this;
    }

    public function getCredentials(): ?array
    {
        return $this->credentials;
    }

    public function setCredentials(array $credentials): self
    {
        $this->credentials = $credentials;

        return $this;
    }

    public static function getDefaultSearchFieldName(): string
    {
        return 'date';
    }

    public function getSearchCardTitle(): string
    {
        return $this->id;
    }

    public function getSearchCardBody(): string
    {
        return $this->consumer->getSearchCardBody() . ' : ' . $this->quantity;
    }

    # TODO Change with product image later
    public function getSearchCardImage(): ?string
    {
        return null;
    }
}
