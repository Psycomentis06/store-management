<?php

namespace App\Entity;

use App\Repository\UploadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: UploadRepository::class)]
class Upload
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $originalName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 10)]
    private string $type;

    #[ORM\Column(type: 'string', length: 20)]
    private string $mimeType;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'images')]
    private Collection $physicalProducts;

    #[Pure] public function __construct()
    {
        $this->physicalProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getPhysicalProducts(): Collection
    {
        return $this->physicalProducts;
    }

    public function addPhysicalProduct(Product $physicalProduct): self
    {
        if (!$this->physicalProducts->contains($physicalProduct)) {
            $this->physicalProducts[] = $physicalProduct;
            $physicalProduct->addImage($this);
        }

        return $this;
    }

    public function removePhysicalProduct(Product $physicalProduct): self
    {
        if ($this->physicalProducts->removeElement($physicalProduct)) {
            $physicalProduct->removeImage($this);
        }

        return $this;
    }
}
