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
    public function getCurrency(Request $request)
    {
        $request = $request->toArray();
        $result = [];
        $result["rateCurrency"] = "RUR";
        $result["rateSum"] = 1;
        $result["rate"] = "70";

        foreach ($request as $key => $value) {
            if ($key == "currency") {
                if ($value == null) {
                    return new JsonResponse('Заполните необходимые поля');
                } else if ($value == "USD") {
                    $result["name"] = "Доллар";
                    $result["code"] = "USD";
                }
                else {
                    return new JsonResponse('В currency введен неизвестный код валют (Возможено вы хотели ввести USD)');
                }
            }

            if ($key == "rateCurrency") {
                if ($value !== null && $value !== "RUR") {
                    return new JsonResponse('В rateCurrency введен неизвестный код валют (Возможено вы хотели ввести RUR)');
                }
            }

            if ($key == "rateSum") {
                if ($value !== null && $value !== 1) {
                    if (is_numeric($value)) {
                        $result["rateSum"] = $value;
                        $result["result"] = $result["rate"] * $result["rateSum"];
                    } else {
                        return new JsonResponse('rateSum должен являться числом');
                    }
                }
            }
        }

        return new JsonResponse($result);
    }
}