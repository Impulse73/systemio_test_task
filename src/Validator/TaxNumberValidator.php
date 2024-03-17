<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TaxNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var TaxNumber $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $value = strtoupper($value);
        $countryCode = substr($value, 0, 2);
        $res = '';

        if ($countryCode == 'FR') {
            preg_match('/[a-zA-Z]{4}[0-9]+/', $value, $res);
        } else {
            preg_match('/[a-zA-Z]{2}[0-9]+/', $value, $res);
        }

        if (!empty($res) && array_shift($res) !== $value) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation(TaxNumber::WRONG_TAX);
        }
    }
}
