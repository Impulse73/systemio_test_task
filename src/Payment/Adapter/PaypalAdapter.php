<?php

namespace App\Payment\Adapter;

use App\Payment\Processor\PaypalPaymentProcessor;

class PaypalAdapter implements IPayment
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

       /* @var PaypalPaymentProcessor $processor */
       $processor = new $processorClass();
        try {
            $processor->pay($price);
        } catch (\Exception $e) {
             return ['error' => $e->getMessage()];
        }

        return ['success' => true];
    }

    /**
     * @return string
     */
    public function getPaymentProcessor(): string
    {
       return PaypalPaymentProcessor::class;
    }
}