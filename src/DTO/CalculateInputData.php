<?php

namespace App\DTO;

use App\Validator\TaxNumber;
use App\Validator\TaxNumberValidator;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CalculateInputData
{
    protected $product;

    protected $taxNumber;

    protected $taxCountryCode;

    protected $couponCode = null;


    protected ValidatorInterface $validator;

    /**
     * @param $inputData
     * @param ValidatorInterface $validator
     */
    public function __construct($inputData, ValidatorInterface $validator)
    {
        $this->product = $inputData['product'] ?? '';
        $this->taxNumber = $inputData['taxNumber'] ?? '';
        $this->couponCode = $inputData['couponCode'] ?? '';
        $this->taxCountryCode = substr($this->taxNumber, 0, 2);

        $this->validator = $validator;
    }

    /**
     * @return array
     */
    public function validate(): array
    {
        $violationList = [];
        $formattedErrors = [];

        $violationList[] = $this->validator->validate($this->taxNumber, [new NotBlank(), new TaxNumber()]);
        $violationList[] = $this->validator->validate($this->taxNumber, [new NotBlank(), new NotNull()]);
        $violationList[] = $this->validator->validate($this->taxNumber, [new NotBlank()]);

        foreach ($violationList as $violation) {
            $violationObj = $violation->has(0) ? $violation->get(0) : null;
            if ($violationObj instanceof ConstraintViolationInterface) {
                $formattedErrors[] = $violationObj->getMessage();
            }
        }

        return $formattedErrors;
    }

    /**
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param string $product
     */
    public function setProduct(string $product): void
    {
        $this->product = $product;
    }

    /**
     * @return string
     */
    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    /**
     * @param string $taxNumber
     */
    public function setTaxNumber(string $taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }

    /**
     * @return string|null
     */
    public function getCouponCode()
    {
        return $this->couponCode;
    }

    /**
     * @param string|null $couponCode
     */
    public function setCouponCode(?string $couponCode): void
    {
        $this->couponCode = $couponCode;
    }

    /**
     * @return string
     */
    public function getTaxCountryCode()
    {
        return $this->taxCountryCode;
    }

    /**
     * @param string $taxCountryCode
     */
    public function setTaxCountryCode(string $taxCountryCode): void
    {
        $this->taxCountryCode = $taxCountryCode;
    }
}