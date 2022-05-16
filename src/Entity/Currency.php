<?php

namespace App\Entity;

use App\_Interface\SearchableEntityInterface;
use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[UniqueEntity(fields: ['currency', 'currencyFullName'], message: 'Currency and Currency\'s Name Should be unique')]
class Currency implements SearchableEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 3)]
    private string $currency = '';

    #[ORM\Column(type: 'string', length: 20)]
    private string $currencyFullName = '';

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $symbol = '';

    #[ORM\OneToMany(mappedBy: 'currency', targetEntity: Product::class)]
    private Collection $products;

    #[Pure] public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCurrencyFullName(): ?string
    {
        return $this->currencyFullName;
    }

    public function setCurrencyFullName(string $currencyFullName): self
    {
        $this->currencyFullName = $currencyFullName;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCurrency($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCurrency() === $this) {
                $product->setCurrency(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->symbol . ' : ' . strtoupper($this->currency) . ' - ' . $this->currencyFullName;
    }

    public static function getDefaultSearchFieldName(): string
    {
        return 'currency';
    }

    public function getSearchCardTitle(): string
    {
        return $this->currency;
    }

    public function getSearchCardBody(): string
    {
        return $this->currencyFullName;
    }

    public function getSearchCardImage(): ?string
    {
        return null;
    }
}
