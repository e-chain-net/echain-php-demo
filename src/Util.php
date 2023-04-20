<?php

namespace EChainDemo;

use Unirest\Request;
use Unirest\Request\Body;
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
        return $msectimes = substr($msectime,0,13);
    }

    public static function requestBlockNumber($url,$rsaPrivate){
        
    }
    
    private static function httpPost($url,$param){
        $headers = array('Accept' => 'application/json');
        // $data = array('name' => 'ahmad', 'company' => 'mashape');
    
        $body = Body::json($param);
        $response = Request::post($url, $headers, $body);
    
        return $response;
    }    
    
    public static function http_post($url,$request,$mechatNo,$rsa) : object{
      $timestamp = '' . self::getMilliseconds();
      $signature = $rsa->sign($mechatNo . '-' . $timestamp);
    
      $curl = curl_init();
    
      curl_setopt_array($curl, [
        CURLOPT_PORT => "8080",
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
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
}
