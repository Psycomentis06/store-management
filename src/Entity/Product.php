<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private string $description;

    #[ORM\Column(type: 'string', length: 20)]
    private string $sku;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private int $discount = 0;

    #[ORM\Column(type: 'integer', nullable: true)]
    private int $guarantee = 0;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $properties = [];

    #[ORM\Column(type: 'boolean')]
    private bool $digital;

    #[ORM\Column(type: 'integer', nullable: true)]
    private int $price = 0;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private array $images = [];

    #[ORM\ManyToOne(targetEntity: Currency::class, inversedBy: 'products')]
    private Currency $currency;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getGuarantee(): ?int
    {
        return $this->guarantee;
    }

    public function setGuarantee(?int $guarantee): self
    {
        $this->guarantee = $guarantee;

        return $this;
    }

    public function getProperties(): ?array
    {
        return $this->properties;
    }

    public function setProperties(?array $properties): self
    {
        $this->properties = $properties;

        return $this;
    }

    public function getDigital(): ?bool
    {
        return $this->digital;
    }

    public function setDigital(bool $digital): self
    {
        $this->digital = $digital;

        return $this;
    }


    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
