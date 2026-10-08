<?php
namespace App\Entity;

use App\Repository\WarehouseOrderRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: WarehouseOrderRepository::class)]
class WarehouseOrder implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $customerName = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

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
    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    /**
     * @param string $customerName
     * @return $this
     */
    public function setCustomerName(string $customerName): static
    {
        $this->customerName = $customerName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id'            => $this->id,
            'customer_name' => $this->customerName,
            'status'        => $this->status
        ];
    }
}