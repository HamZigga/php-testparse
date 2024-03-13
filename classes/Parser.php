<?php

/**
 * Parser Class
 */
class Parser
{
    public function parseLot($bidding, $lotNumber)
    {
        $urlparameters = array(
            'lot_description' => '',
            'trade_number' => $bidding,
            'lot_number' => $lotNumber,
            'debtor_info' => '',
            'arbitr_info' => '',
            'app_start_from' => '',
            'app_start_to' => '',
            'app_end_from' => '',
            'app_end_to' => '',
            'trade_type' => 'Любой',
            'trade_state' => 'Любой',
            'trade_org' => '',
            'pagenum' => '',
        );

        $findLot = $this->getContent('https://nistp.ru/?' . http_build_query($urlparameters));

        $urlpattern = '/https:\/\/nistp\.ru\/bankrot\/trade_view\.php\?trade_nid=(?P<digit>\d+)/';

        preg_match($urlpattern, $findLot, $urlToLot);

        if (isset($urlToLot[0])) {
            $findLotinfo = $this->getContent($urlToLot[0]);
            return [$findLotinfo, $urlToLot[0]];
        }
        return null;
    }

    public function getContent($url)
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3' . PHP_EOL,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        return file_get_contents($url, false, $context);
    }

    public function getLotInfo($bidding, $lotNumber)
    {
        $LotResult = $this->parseLot($bidding, $lotNumber);
        if (isset($LotResult)) {
            $findLotinfo = $LotResult[0];

            $lotPattern = '/Лот № ' . $lotNumber . '(?P<result>.*?)<td colspan=2/s';
            preg_match('/Информация о должнике(?P<result>.*?)<\/table>/', $findLotinfo, $debtorInfoResult);
            preg_match($lotPattern, $findLotinfo, $LotInfoBlockResult);
            if(is_null($LotInfoBlockResult)){
                return null;
            }
            preg_match('/на торги, его составе, характеристиках, описание<\/td>\W*<td>(?P<result>.*?)<\/td>/s', $LotInfoBlockResult['result'], $lotInfoResult);
            preg_match('/Начальная цена<\/td>\W*<td>(?P<result>.*?)<\/td>/', $LotInfoBlockResult['result'], $lotStartingPriceResult);
            preg_match('/ИНН<\/td>\W*<td>(?P<result>.*?)<\/td>/', $debtorInfoResult['result'], $innResult);

            preg_match('/E-mail<\/td>\W*<td>(?P<result>.*?)<\/td>/', $findLotinfo, $emailResult);
            preg_match('/Телефон<\/td>\W*<td>\+(?P<result>.*?)<\/td>/', $findLotinfo, $phoneResult);
            preg_match('/Номер дела о банкротстве<\/td>\W*<td>(?P<result>.*?)<\/td>/', $findLotinfo, $caseNumberResult);
            preg_match('/Дата начала представления заявок на участие<\/td>\W*<td>(?P<result>.*?)<\/td>/', $findLotinfo, $startDateResult);
            preg_match('/Дата окончания представления заявок на участие<\/td>\W*<td>(?P<result>.*?)<\/td>/', $findLotinfo, $endDateResult);

            $result = [
                "urlToLot"  => $LotResult[1],
                "lotNumber" => $lotNumber,
                "lotInfo" => $lotInfoResult['result'],
                "lotStartingPrice" => $lotStartingPriceResult['result'],
                "email" => $emailResult['result'],
                "phone" => $phoneResult['result'],
                "inn" => $innResult['result'],
                "caseNumber" => $caseNumberResult['result'],
                "startDate" => date("Y-m-d H:i:s", strtotime($startDateResult['result'])),
                "endDate" => date("Y-m-d H:i:s", strtotime($endDateResult['result'])),

            ];

            return $result;
        }
        return null;
    }
}
