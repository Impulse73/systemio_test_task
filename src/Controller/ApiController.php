<?php

namespace App\Controller;

use App\DTO\CalculateInputData;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Tax;
use App\Payment\Adapter\IPayment;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    protected ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $manager)
    {
        $this->managerRegistry = $manager;
    }

    /**
     * @Route("/calculate-price", name="calcPrice")
     * @param ValidatorInterface $validator
     * @param Request $request
     * @return Response
     */
    public function calculatePriceAction(ValidatorInterface $validator, Request $request): Response
    {
        $response =  new Response();
        $response->headers->set('Content-Type', 'text/plain');
        $response->setStatusCode(400);

        $content = $this->calculatePrice($request, $validator);

        if (!isset($content['error'])) {
            $response->setStatusCode(200);
        }

        $response->setContent(json_encode($content));
        return $response->send();
    }

    /**
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return array
     */
    protected function calculatePrice(Request $request, ValidatorInterface $validator): array
    {
        $requestData = json_decode($request->getContent(), true);
        $data = new CalculateInputData($requestData, $validator);
        $errors = $data->validate();

        if (!empty($errors)) {
            return ['error' => $errors];
        }

        $manager = $this->managerRegistry;
        $productEntity = $manager->getRepository(Product::class)->find($data->getProduct());
        $taxEntity = $manager->getRepository(Tax::class)->findOneBy(['code' => $data->getTaxCountryCode()]);
        $couponEntity = $manager->getRepository(Coupon::class)->findOneBy(['name' => $data->getCouponCode()]);

        if (!$productEntity) {
            return ['error' => 'Product not found'];
        } elseif (!$taxEntity) {
            return ['error' => 'Tax not found'];
        } elseif (!$couponEntity) {
            return ['error' => 'Coupon not found'];
        }

        $productPrice = $productEntity->getPrice();
        $taxCost = $taxEntity->getTaxPercent();
        $priceWithTax = $productPrice + $productPrice * $taxCost;

        if ($couponEntity->getDiscountType() == 0) {
            $resultPrice = $priceWithTax - ($priceWithTax / 100 * $couponEntity->getPercentDiscount());
        } else {
            $resultPrice = $priceWithTax - ($priceWithTax / 100 * $couponEntity->getValueDiscount());
        }

        return ['price' => $resultPrice];
    }

    /**
     * @Route("/purchase", name="purchaseAct")
     * @param ValidatorInterface $validator
     * @param Request $request
     * @return Response
     */
    public function purchaseAction(ValidatorInterface $validator, Request $request) {
        $response =  new Response();
        $response->headers->set('Content-Type', 'text/plain');
        $response->setStatusCode(400);
        $requestData = json_decode($request->getContent(), true);
        $paymentMethod = $requestData['paymentProcessor'] ?? null;
        $errors = $validator->validate($paymentMethod, new NotBlank());

        if ($errors->has(0)) {
            return $response->setContent(json_encode($errors->get(0)->getMessage()))->send();
        }

        $paymentAdapter = $this->getPaymentAdapter($paymentMethod);

        if (!isset($paymentAdapter)) {
            return $response->setContent(json_encode(['Payment method not found']))->send();
        }

        $content = $this->calculatePrice($request, $validator);
        $price = $content['price'] ?? false;
        $paymentResult = [];

        if ($price) {
            $paymentResult = $paymentAdapter->doPayment($price);
            $response->setContent(json_encode($paymentResult));
        }

        if (!isset($paymentResult['error']) && $price && isset($content['error'])) {
            $response->setStatusCode(200);
        }

        return $response->send();
    }

    /**
     * @param string $paymentMethod
     * @return IPayment|null
     */
    protected function getPaymentAdapter(string $paymentMethod): ?IPayment
    {
        if (empty($paymentMethod) || $paymentMethod == '') {
            return null;
        }

        /*
           можно было сделать хранение платежных методов в бд
           и забирать оттуда инфу о платежном процесоре по
           коду платежного метода
        */
        $paymentMethod = strtolower($paymentMethod);
        $paymentMethod = ucfirst($paymentMethod);
        $paymentAdapterClass = "App\\Payment\\Adapter\\{$paymentMethod}Adapter";
        if (class_exists($paymentAdapterClass)) {
            $paymentAdapter = new $paymentAdapterClass();
            if ($paymentAdapter instanceof IPayment) {
                return $paymentAdapter;
            }
        }

        return null;
    }
}
