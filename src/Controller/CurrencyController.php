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
        if (!array_key_exists("currency", $request)) {
            return new JsonResponse('currency является обязательным полем запроса');
        }

        $result = [];
        $result["rateCurrency"] = "RUR";
        $result["rateSum"] = 1;
        $requiredСurrency = null;
        $date = date('Y-m-d');
        $info = file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js?"disclaimer"="https://www.cbr-xml-daily.ru/%23terms"&"date"="' . $date . '"&"rates"="' . $requiredСurrency . '"');
        $info = json_decode($info, true);

        foreach ($request as $key => $value) {
            if ($key == "currency") {
                if ($value == null) {
                    return new JsonResponse('Заполните необходимые поля');
                } else {
                    if (array_key_exists($value, $info["Valute"])) {
                        $result["rate"] = $info["Valute"][$value]["Value"];
                        $result["name"] = $info["Valute"][$value]["Name"];
                        $result["code"] = $info["Valute"][$value]["CharCode"];
                    } else {
                        return new JsonResponse('В currency введен неизвестный код валют (Возможено вы хотели ввести USD)');
                    }

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
                    } else {
                        return new JsonResponse('rateSum должен являться числом');
                    }
                }
            }
        }

        $result["result"] = $result["rate"] * $result["rateSum"];

        return new JsonResponse($result);
    }
}
