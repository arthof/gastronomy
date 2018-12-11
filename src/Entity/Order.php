<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 */
class Order
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
     * @ORM\Column(type="datetime")
     */
    private $orderDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderDish", mappedBy="associatedOrder")
     */
    private $orderDishes;

    public function __construct()
    {
        $now= new \DateTime(date('Y-m-d H:i:s'));
        $this->setOrderDate($now);
        $this->orderDishes = new ArrayCollection();
        $this->setState('active');
        $this->setName('Order #');
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

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection|OrderDish[]
     */
    public function getOrderDishes(): Collection
    {
        return $this->orderDishes;
    }

    public function addOrderDish(OrderDish $orderDish): self
    {
        if (!$this->orderDishes->contains($orderDish)) {
            $this->orderDishes[] = $orderDish;
            $orderDish->setAssociatedOrder($this);
        }

        return $this;
    }

    public function removeOrderDish(OrderDish $orderDish): self
    {
        if ($this->orderDishes->contains($orderDish)) {
            $this->orderDishes->removeElement($orderDish);
            // set the owning side to null (unless already changed)
            if ($orderDish->getAssociatedOrder() === $this) {
                $orderDish->setAssociatedOrder(null);
            }
        }

        return $this;
    }
}
