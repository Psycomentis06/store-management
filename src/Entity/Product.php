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

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $guarantee;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $properties = [];

    #[ORM\Column(type: 'boolean')]
    private bool $digital;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductPrice::class)]
    private Collection $price;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private array $images = [];

    #[Pure] public function __construct()
    {
        $this->price = new ArrayCollection();
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

    public function getGuarantee(): ?\DateTimeInterface
    {
        return $this->guarantee;
    }

    public function setGuarantee(?\DateTimeInterface $guarantee): self
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

    /**
     * @return Collection<int, ProductPrice>
     */
    public function getPrice(): Collection
    {
        return $this->price;
    }

    public function addPrice(ProductPrice $price): self
    {
        if (!$this->price->contains($price)) {
            $this->price[] = $price;
            $price->setProduct($this);
        }

        return $this;
    }

    public function removePrice(ProductPrice $price): self
    {
        if ($this->price->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getProduct() === $this) {
                $price->setProduct(null);
            }
        }

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
}
