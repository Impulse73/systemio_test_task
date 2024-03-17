<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class TaxNumber extends Constraint
{
    public const WRONG_TAX = 'wrong_tax';

    protected static $errorNames = [
        self::WRONG_TAX => 'wrong_tax',
    ];
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'The Taxnumber "{{ value }}" is not valid.';

    /**
     * @param array|null $groups
     * @param $payload
     */
    public function __construct(
        array $groups = null,
        $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }
}
