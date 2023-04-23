<?php

namespace EChainDemo;

use Unirest\Request;
use Unirest\Request\Body;
use EChain\Formatter;
use Exception;

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

    public static function generateAccounts($sdk,$count){
      $accounts = array();
      for($i = 0; $i < $count; $i++){
        array_push($accounts,$sdk->generateAccount());
      }
      return $accounts;
    }

    /**
     * 请求当前最新区块号
     * @param $url 请求地址
     * @param $merchantNo 用户商编
     * @param $rsa RSA对象
     * @return 当前链上最新区块号，请求失败抛出异常
     */
    public static function requestBlockNumber($url,$mechatNo,$rsa){
      $blockNumberReq = [
        'jsonRpc' => [
          'method'=>'getBlockNumber',
          'params'=>[]
        ]
      ];
      $response = self::http_post($url,$blockNumberReq,$mechatNo,$rsa);
      if($response->error != ""){
        throw new Exception($response->error);
      }
      $obj = json_decode($response->response);
      if($obj->code != "EC000000"){
        throw new Exception($obj->message);
      }
      return $obj->data->blockNumber;
    }
    /**
     * 请求交易收据
     * @param $url 请求地址
     * @param $merchantNo 用户商编
     * @param $rsa RSA对象
     * @param $txHash 交易哈希
     * @return 交易收据，请求失败抛出异常，交易收据中statusOK为true代表交易上链成功，statusOK为false，表示交易上链执行失败
     */
    public static function requestTransactionReceipt($url,$mechatNo,$rsa,$txHash){
      $getTxReceiptReq = [
        'jsonRpc' => [
          'method'=>'getTransactionReceipt',
          'params'=>[$txHash,false]
        ]
      ];
      $response = self::http_post($url,$getTxReceiptReq,$mechatNo,$rsa);
      if($response->error != ""){
        throw new Exception($response->error);
      }
      $obj = json_decode($response->response);
      if($obj->code != "EC000000"){
        throw new Exception($obj->message);
      }
      return $obj->data;
    }

    /**
     * 请求tokenOwner地址
     * @param $url 请求地址
     * @param $merchantNo 用户商编
     * @param $rsa RSA对象
     * @param $contractAddress 合约地址
     * @param $tokenId  nft-id
     * @return nft的所有者地址，请求失败抛出异常，nft未铸造，返回空字符串
     */
    public static function requestTokenOwner($url,$merchantNo,$rsa,$contractAddress,$tokenId){
      $inputOwnerOf = self::formatInputOwnerOf($tokenId);
      // echo "Input for ownerOf:" . $inputOwnerOf . "\n";
      $getOwnerOfReq = [
        'jsonRpc' => [
          'method'=>'call',
          'params'=>[$contractAddress,$inputOwnerOf]
        ]
      ];

      $response = Util::http_post($url,$getOwnerOfReq,$merchantNo,$rsa);
      
      // Util::echo_response($response);
      
      $obj = json_decode($response->response);
      if($obj->code != "EC000000"){
        throw new Exception($obj->message);
      }
      $output = $obj->data->jsonRpcResp->result->output;
      if(strlen($output) == 66){
        return "0x" . substr($output,26);
      }else{
        return "";
      }
    }

   /**
     * 请求交易收据
     * @param $url 请求地址
     * @param $merchantNo 用户商编
     * @param $rsa RSA对象
     * @param $txHash 交易哈希
     * @param $txSigned 签名后的交易体
     * @return bool true代表发送成功，false代表发送失败
     */
    public static function requestSendTx($url,$mechatNo,$rsa,$txHash,$txSigned){
      $sendTxReq = [
        'reqNo'=>$txHash,
        'jsonRpc' => [
          'method'=>'sendTransaction',
          'params'=>[$txSigned,false]
        ]
      ];
      $response = self::http_post($url,$sendTxReq,$mechatNo,$rsa);
      if($response->error != ""){
        throw new Exception($response->error);
      }
      $obj = json_decode($response->response);
      if($obj->code != "EC000001"){
        return false;
      }
      return true;
    }
}
