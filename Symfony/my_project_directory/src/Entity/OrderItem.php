<?php
namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?WarehouseOrder $warehouseOrder = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?InventoryItem $inventoryItem = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return WarehouseOrder|null
     */
    public function getWarehouseOrder(): ?WarehouseOrder
    {
        return $this->warehouseOrder;
    }

    /**
     * @param WarehouseOrder $warehouseOrder
     * @return $this
     */
    public function setWarehouseOrder(?WarehouseOrder $warehouseOrder): static
    {
        $this->warehouseOrder = $warehouseOrder;
        return $this;
    }

    /**
     * @return InventoryItem|null
     */
    public function getInventoryItem(): ?InventoryItem
    {
        return $this->inventoryItem;
    }

    /**
     * @param InventoryItem $inventoryItem
     * @return $this
     */
    public function setInventoryItem(?InventoryItem $inventoryItem): static
    {
        $this->inventoryItem = $inventoryItem;
        return $this;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id'                => $this->id,
            'quantity'          => $this->quantity,
            'order_id'          => $this->warehouseOrder ? $this->warehouseOrder->getId() : null,
            'inventory_item_id' => $this->inventoryItem ? $this->inventoryItem->getId() : null
        ];
    }
}