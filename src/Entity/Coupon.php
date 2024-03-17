<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CouponRepository::class)
 */
class Coupon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $discountType;

    /**
     * @ORM\Column(type="integer")
     */
    private $percentDiscount;

    /**
     * @ORM\Column(type="integer")
     */
    private $valueDiscount;

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

    public function getDiscountType(): ?int
    {
        return $this->discountType;
    }

    public function setDiscountType(int $discountType): self
    {
        $this->discountType = $discountType;

        return $this;
    }

    public function getPercentDiscount(): ?int
    {
        return $this->percentDiscount;
    }

    public function setPercentDiscount(int $percentDiscount): self
    {
        $this->percentDiscount = $percentDiscount;

        return $this;
    }

    public function getValueDiscount(): ?int
    {
        return $this->valueDiscount;
    }

    public function setValueDiscount(int $valueDiscount): self
    {
        $this->valueDiscount = $valueDiscount;

        return $this;
    }
}
