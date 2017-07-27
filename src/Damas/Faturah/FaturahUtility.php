<?php
namespace Damas\Faturah;

class FaturahService{
    const URL='https://Services.faturah.com/TokenGeneratorWS.asmx?wsdl';
    private static $client;

    private function __construct(){
        //self::$client= new SoapClient(self::URL);
    }

    public static function call($name,$params)
    {
        self::$client= new \SoapClient(self::URL);
        return self::$client->__soapCall($name, $params);
    }
}

class FaturahUtility{

    public static function generateToken($code){
        $params = array('GenerateNewMerchantToken'=>array('merchantCode'=>$code));
        return FaturahService::call('GenerateNewMerchantToken', $params)->GenerateNewMerchantTokenResult;
    }

    public static function isSecureMerchant($code){
        $params = array('IsSecuredMerchant'=>array("merchantCode"=>$code));
        return FaturahService::call('IsSecuredMerchant', $params)->IsSecuredMerchantResult;
    }

    public static function generateSecureHash($message){
        $params = array('GenerateSecureHash'=>array("message"=>$message));
        return FaturahService::call('GenerateSecureHash', $params)->GenerateSecureHashResult;
    }

    public static function constructMessage($key, $code, $token, $order){
        $productIDs=implode('|', $order->productIDs);
        $productQuantities=implode('|', $order->productQuantities);
        $productPrices=implode('|', $order->productPrices);
        return $key.$code.$token.$order->totalPrice.$order->deliveryCharge.$productIDs.$productQuantities.$productPrices.$order->email.$order->lang;
    }

    public static function convertCurrency($from_Currency, $to_Currency='SAR'){

        $amount=1;
        $amount = urlencode($amount);
        $from_Currency = urlencode($from_Currency);
        $to_Currency = urlencode($to_Currency);

        $url = "http://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency";

        $ch = curl_init();
        $timeout = 0;
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt ($ch, CURLOPT_USERAGENT,
            "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $rawdata = curl_exec($ch);
        curl_close($ch);
        $data = explode('bld>', $rawdata);
        $data = explode($to_Currency, $data[1]);

        return round($data[0], 2);


    }

    public function alert($msg){
        echo '<script type="text/javascript">alert("'.$msg.'");</script>';
    }
}