<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CurrencyController extends AbstractController
{
    /**
     * @Route("/currency", name="get_currency", methods={"POST"})
     */
    public function getCurrency (Request $request)
    {
        $request = $request->toArray();
        $result = [];

    foreach ($request as $key => $value)
    {
        if ($key == "currency"){
            if ($value == null){
                return new JsonResponse('Заполните необходимые поля');
            }
            else if ($value == "USD") {
                $result["name"] = "Доллар";
                $result["code"] = "USD";
            }
        }

        if ($key == "rateCurrency"){
            if ($value == null || $value = "RUR"){
                $result["rateCurrency"] = "RUR";
                $result["rate"] = "70";
            }
            else {
                $result["rateCurrency"] = $value;
            }
        }

        if ($key == "rateSum"){
            if ($value == null || $value == 1){
                $result["rateSum"] = "1";
                $result["result"] = $result["rate"] * $result["rateSum"];
            }
            else {
                $result["rateSum"] = $value;
                $result["result"] = $result["rate"] * $result["rateSum"];
            }
        }
    }

        return new JsonResponse([
            'name' => $result["name"],
            'code' => $result["code"],
            'result' => $result["result"],
            'rateCurrency' => $result["rateCurrency"],
            'rateSum' => $result["rateSum"],
            'rate' => $result["rate"]]
        );
    }

}