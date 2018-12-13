<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderDishRepository")
 */
class OrderDish
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderDishes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $associatedOrder;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, options={"default": "0.00"})
     */
    private $price;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Dish", cascade={"persist", "remove"})
     */
    private $associatedDish;
    
    public function setFromDish(Dish $dish)
    {
        $this->setName($dish->getName());
        $this->setPrice($dish->getPrice());
        $this->setAssociatedDish($dish);
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

    public function getAssociatedOrder(): ?Order
    {
        return $this->associatedOrder;
    }

    public function setAssociatedOrder(?Order $associatedOrder): self
    {
        $this->associatedOrder = $associatedOrder;

        return $this;
    }
    
    public function getData()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAssociatedDish(): ?Dish
    {
        return $this->associatedDish;
    }

    public function setAssociatedDish(?Dish $associatedDish): self
    {
        $this->associatedDish = $associatedDish;

        return $this;
    }
    
}
