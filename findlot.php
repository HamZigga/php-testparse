<?php
include_once 'classes/Bankrupt.php';
include_once 'classes/Lot.php';
include_once 'classes/Parser.php';


if (isset($_GET["biddingNumber"]) && isset($_GET["lotNumber"])) {
    $bidding = $_GET["biddingNumber"];
    $lotNumber = $_GET["lotNumber"];

    $lotInfoResult = (new Parser)->getLotInfo($bidding, $lotNumber);
    if (!is_null($lotInfoResult)) {
        (new Bankrupt)->store([
            $lotInfoResult['email'], $lotInfoResult['phone'], $lotInfoResult['inn'],
            $lotInfoResult['caseNumber'], $lotInfoResult['urlToLot'], $lotInfoResult['startDate'], $lotInfoResult['endDate']
        ]);
        (new Lot)->store([
            $lotInfoResult['inn'], $lotInfoResult['lotNumber'],
            $lotInfoResult['lotInfo'], $lotInfoResult['lotStartingPrice']
        ]);

        header('Location: ' . $lotInfoResult['urlToLot']);
        exit();
    } else {
        setcookie('LotNotFound', time() + 3600);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
} else {
    setcookie('LotNotFound', "", time() + 3600);
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
