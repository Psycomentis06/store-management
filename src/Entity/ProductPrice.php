<?php

namespace App\Entity;

use App\Repository\ProductPriceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: ProductPriceRepository::class)]
class ProductPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $price = 0;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $fromDay;

    #[ORM\ManyToOne(targetEntity: Currency::class, inversedBy: 'productPrices')]
    private Currency $currency;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'price')]
    private Product $product;

    public function __construct()
    {
        $this->fromDay = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFromDay(): ?\DateTimeInterface
    {
        return $this->fromDay;
    }

    public function setFromDay(\DateTimeInterface $fromDay): self
    {
        $this->fromDay = $fromDay;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
