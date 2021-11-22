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
        if (!array_key_exists("currency", $request)){
            return new JsonResponse('currency является обязательным полем запроса');
        }
        $date = date('Y-m-d');
        $info = file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js?"disclaimer"="https://www.cbr-xml-daily.ru/%23terms"&"date"="'.$date.'"&"timestamp"=1637625600&"base"="RUB"&"rates"="USD"');
        $info = json_decode($info, true);
        $result = [];
        $result["rateCurrency"] = "RUR";
        $result["rateSum"] = 1;
        $result["rate"] = $info["Valute"]["USD"]["Value"];

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