<?php

namespace App\Entity;

use App\Repository\FridgeItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FridgeItemRepository::class)]
#[ORM\Table(name: "FridgeItem")]
class FridgeItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Fridge $fridge = null;

    #[ORM\ManyToOne]
    private ?Item $item = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $expiration_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFridge(): ?Fridge
    {
        return $this->fridge;
    }


    public function setFridge(?Fridge $fridge): static
    {
        $this->fridge = $fridge;
        return $this;
    }

    public function getItem(): ?Item
{
    return $this->item;
}

    public function setItem(?Item $item): static
{
    $this->item = $item;

    return $this;
}

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(?\DateTimeInterface $expiration_date): static
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }
}
