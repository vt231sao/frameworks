<?php
namespace App\Entity;

use App\Repository\SupplierRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: SupplierRepository::class)]
class Supplier implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $contactEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

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
    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    /**
     * @param string $contactEmail
     * @return $this
     */
    public function setContactEmail(string $contactEmail): static
    {
        $this->contactEmail = $contactEmail;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'contact_email' => $this->contactEmail,
            'phone'         => $this->phone
        ];
    }
}