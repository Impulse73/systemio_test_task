<?php

namespace App\Payment\Adapter;


use App\Payment\Processor\StripePaymentProcessor;

class StripeAdapter implements IPayment
{

    /**
     * @param $price
     * @return array
     */
    public function doPayment($price): array
    {
        $processorClass = $this->getPaymentProcessor();

        if (!class_exists($processorClass)) {
            return ['error' => 'Payment processor not found'];
        }

        /* @var StripePaymentProcessor $processor */
        $processor = new $processorClass();
        try {
            $result = $processor->processPayment($price);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        return ['success' => $result];
    }

    /**
     * @return string
     */
    public function getPaymentProcessor()
    {
        return StripePaymentProcessor::class;
    }
}