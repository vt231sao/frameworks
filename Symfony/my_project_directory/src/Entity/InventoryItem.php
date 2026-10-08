<?php
namespace App\Entity;

use App\Repository\InventoryItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: InventoryItemRepository::class)]
class InventoryItem implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private ?int $stockQuantity = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Category $category = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Supplier $supplier = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice(string $price): static
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStockQuantity(): ?int
    {
        return $this->stockQuantity;
    }

    /**
     * @param int $stockQuantity
     * @return $this
     */
    public function setStockQuantity(int $stockQuantity): static
    {
        $this->stockQuantity = $stockQuantity;
        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return Supplier|null
     */
    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    /**
     * @param Supplier|null $supplier
     * @return $this
     */
    public function setSupplier(?Supplier $supplier): static
    {
        $this->supplier = $supplier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'price'          => $this->price,
            'stock_quantity' => $this->stockQuantity,
            'category_id'    => $this->category ? $this->category->getId() : null,
            'supplier_id'    => $this->supplier ? $this->supplier->getId() : null
        ];
    }
}