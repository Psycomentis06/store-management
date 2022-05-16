<?php

namespace App\Entity;

use App\_Interface\SearchableEntityInterface;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer implements SearchableEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 30)]
    private string $firstName;

    #[ORM\Column(type: 'string', length: 30)]
    private string $lastName;

    #[ORM\Column(type: 'string', length: 30, unique: true)]
    private string $phoneNumber;

    #[ORM\Column(type: 'string', length: 30, unique: true)]
    private string $email;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $address = [];

    #[ORM\OneToMany(mappedBy: 'consumer', targetEntity: DigitalPurchase::class)]
    private Collection $digitalPurchases;

    #[Pure] public function __construct()
    {
        $this->digitalPurchases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

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
            $digitalPurchase->setConsumer($this);
        }

        return $this;
    }

    public function removeDigitalPurchase(DigitalPurchase $digitalPurchase): self
    {
        if ($this->digitalPurchases->removeElement($digitalPurchase)) {
            // set the owning side to null (unless already changed)
            if ($digitalPurchase->getConsumer() === $this) {
                $digitalPurchase->setConsumer(null);
            }
        }

        return $this;
    }

    public static function getDefaultSearchFieldName(): string
    {
        return 'phoneNumber';
    }

    public function getSearchCardTitle(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getSearchCardBody(): string
    {
        return "Email : $this->email, Phone Number: $this->phoneNumber";
    }

    public function getSearchCardImage(): ?string
    {
        return null;
    }
}
