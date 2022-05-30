<?php

namespace App\Entity;

use App\Repository\InventoryItemRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: InventoryItemRepository::class)]
class InventoryItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;


    #[ORM\Column(type: 'integer')]
    private ?int $stock = 0;

    #[ORM\ManyToOne(targetEntity: Inventory::class, inversedBy: 'items')]
    private Inventory $inventory;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'inventoryItems')]
    private Product $product;

    #[Pure] public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getInventory(): ?Inventory
    {
        return $this->inventory;
    }

    public function setInventory(?Inventory $inventory): self
    {
        $this->inventory = $inventory;

        return $this;
    }
}
