<?php

namespace App\Payment\Adapter;

interface IPayment
{
    public function doPayment($price) : array;

    public function getPaymentProcessor();
}