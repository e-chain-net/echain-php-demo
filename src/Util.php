<?php

namespace EChainDemo;

use EChain\Formatter;

class HttpReponse{
    public $response;
    public $error;
  
    public function __construct($response,$error){
      $this->response = $response;
      $this->error = $error;
    }
}

class Util{
    public static function getMilliseconds(){
        list($msec, $sec) = explode(' ', microtime());    
        $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);    
        return substr($msectime,0,13);
    }
    
    public static function http_post($url,$request,$mechatNo,$rsa) : object{
      $timestamp = '' . self::getMilliseconds();
      $signature = $rsa->sign($mechatNo . '-' . $timestamp);
    
      $curl = curl_init();
    
      curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($request),
        CURLOPT_HTTPHEADER => [
          "content-type: application/json",
          "merchantNo: {$mechatNo}",
          "sign: {$signature}",
          "timestamp: {$timestamp}"
        ],
      ]);
      
      $response = curl_exec($curl);
      $err = curl_error($curl);
      
      curl_close($curl);
    
      return new HttpReponse($response,$err);
    }
    
    public static function echo_response($response){
      if ($response->error) {
        echo "cURL Error #:" . $response->error . "\n";
      } else {
        echo  $response->response . "\n";
      }
    }

    public static function formatInputOwnerOf($tokenId){
        $formatTokenId = Formatter::toIntegerFormat($tokenId);
        $formatMethodOwnerOf = Formatter::toMethodFormat("ownerOf(uint256)");
        return "0x{$formatMethodOwnerOf}{$formatTokenId}";
    }

    public static function generateAccounts($sdk,$count){
      $accounts = array();
      for($i = 0; $i < $count; $i++){
        array_push($accounts,$sdk->generateAccount());
      }
      return $accounts;
    }
}
