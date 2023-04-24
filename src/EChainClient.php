<?php

namespace EChainDemo;

use Exception;
use EChain\Rsa;
use EChainDemo\Util;

class EChainClient{
    private $urlQuery;
    private $urlSendTx;
    private $urlDeploy;
    private $merchantNo;
    private $rsa;

    /**
     * * the construtor
     * @param $urlQuery 查询接口请求地址
     * @param $urlSendTx 发送交易接口请求地址
     * @param $merchantNo 用户商编
     * @param $rsaPublic RSA公钥
     * @param $rsaPrivate RSA私钥
     */
    public function __construct($urlBase,$merchantNo,$rsaPublic,$rsaPrivate)
    {
        $this->urlQuery = $urlBase . "/chain/rpc/query";
        $this->urlSendTx = $urlBase . "/chain/rpc/tx";
        $this->urlDeploy = $urlBase . "/chain/contract/deploy";
        $this->merchantNo = $merchantNo;
        $this->rsa = new Rsa($rsaPublic,$rsaPrivate);
    }

       /**
     * 请求当前最新区块号
     * @return 当前链上最新区块号，请求失败抛出异常
     */
    public function requestBlockNumber(){
        $blockNumberReq = [
          'jsonRpc' => [
            'method'=>'getBlockNumber',
            'params'=>[]
          ]
        ];
        $response = Util::http_post($this->urlQuery,$blockNumberReq,$this->merchantNo,$this->rsa);
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
     * 请求tokenOwner地址
     * @param $contractAddress 合约地址
     * @param $tokenId  nft-id
     * @return nft的所有者地址，请求失败抛出异常，nft未铸造，返回空字符串
     */
    public function requestTokenOwner($contractAddress,$tokenId){
        $inputOwnerOf = Util::formatInputOwnerOf($tokenId);
        // echo "Input for ownerOf:" . $inputOwnerOf . "\n";
        $getOwnerOfReq = [
          'jsonRpc' => [
            'method'=>'call',
            'params'=>[$contractAddress,$inputOwnerOf]
          ]
        ];
  
        $response = Util::http_post($this->urlQuery,$getOwnerOfReq,$this->merchantNo,$this->rsa);
        
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
     * @param $txHash 交易哈希
     * @return 交易收据，请求失败抛出异常，交易收据中statusOK为true代表交易上链成功，statusOK为false，表示交易上链执行失败
     */
    public function requestTransactionReceipt($txHash){
        $getTxReceiptReq = [
          'jsonRpc' => [
            'method'=>'getTransactionReceipt',
            'params'=>[$txHash,false]
          ]
        ];
        $response = Util::http_post($this->urlQuery,$getTxReceiptReq,$this->merchantNo,$this->rsa);
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
     * 请求交易收据
     * @param $txHash 交易哈希
     * @param $txSigned 签名后的交易体
     * @param $callbackUrl 回调Url,交易落块后会通过这个url回调交易上链结果
     * @return bool true代表发送成功，false代表发送失败
     */
    public function requestSendTx($txHash,$txSigned,$callbackUrl){
        $sendTxReq = [
          'reqNo'=>$txHash,
          'jsonRpc' => [
            'method'=>'sendTransaction',
            'params'=>[$txSigned,false]
          ],
          'callbackUrl' => $callbackUrl
        ];
        $response = Util::http_post($this->urlSendTx,$sendTxReq,$this->merchantNo,$this->rsa);
        if($response->error != ""){
          throw new Exception($response->error);
        }
        $obj = json_decode($response->response);
        if($obj->code != "EC000001"){
          return false;
        }
        return true;
    }
    /**
     * 请求部署合约
     * @param $reqNo  请求号，一个标识交易请求的唯一ID
     * @param $owner  合约拥有者地址
     * @return  新部署合约的地址
     */
    public function requestDeployContract($reqNo,$owner){
        $deployReq = [
          'reqNo'=>$reqNo,
          'contractType' => "ERC721",
          'owner' => $owner
        ];
        $response = Util::http_post($this->urlDeploy,$deployReq,$this->merchantNo,$this->rsa);
        if($response->error != ""){
          throw new Exception($response->error);
        }
        $obj = json_decode($response->response);
        if($obj->code != "EC000000"){
          throw new Exception($obj->message);
        }
        return $obj->data->contractAddress;
    }
}