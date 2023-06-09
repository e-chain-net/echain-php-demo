<?php

namespace EChainDemo;

require_once __DIR__ . '/../vendor/autoload.php';

use Exception;

$rsaPublic = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmP4t8L4HqqNYErrB39QLj2FPp0TDS5GXiD6QOmI7xOYkNug0F3a0NFmprLwCs9xhEDMEaprXrBEIS8oCY+MCXTuDw4RjapTm7FkMeDdXMq27xqLk2ztoVIZ3rD9z1ETEQRKSXdQP5KCpoetOouKCnFyrlQcI6ATHTzwjGeEHIXCd9ChS7Yl0/TgaRopu0oBQYip1sTv+AhndSpIsUY+CA9JcmIUZ5wlLxabTfNtleIOcq+70lLB9zOyMVTuEoBcxWYzPab7qNBtr3+dTSfnyt8vaytuP1beu9bBH+fbNvDl7skVxK44HyKA3TNra4idhavI2UFfZFb0eoJ+65mX1JwIDAQAB';
$rsaPrivate = 'MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCY/i3wvgeqo1gSusHf1AuPYU+nRMNLkZeIPpA6YjvE5iQ26DQXdrQ0WamsvAKz3GEQMwRqmtesEQhLygJj4wJdO4PDhGNqlObsWQx4N1cyrbvGouTbO2hUhnesP3PURMRBEpJd1A/koKmh606i4oKcXKuVBwjoBMdPPCMZ4QchcJ30KFLtiXT9OBpGim7SgFBiKnWxO/4CGd1KkixRj4ID0lyYhRnnCUvFptN822V4g5yr7vSUsH3M7IxVO4SgFzFZjM9pvuo0G2vf51NJ+fK3y9rK24/Vt671sEf59s28OXuyRXErjgfIoDdM2triJ2Fq8jZQV9kVvR6gn7rmZfUnAgMBAAECggEAQ248J1BKJr5Jsi+YBaP62F4Gcm3POb5YsFcK0IC9YSIiMgUT+Id8E1q1ewl+k3F9YltqBeZrSk5TfrvxY78JKrhxcbom6zHnuaHh6hZSG2cRTRI8lhfP+vktQ8DPt237pcaetjYiLx1UxqXkicwVzv7VLSDlnwWEJvsVaXGR5/2BT8q+/2VEK4qCe8DESNpWNlDfonXAK0FDtDzWkjwLeWzJtzWQLw0ps8gSTQsUYRA2GUBtcp3MWOy+GOAIzhbTawOYTi3EjvAsRB7YuLYLOnueid0vYVRu6IHETcOBJIpGbBxV0IpbNvYNJ53A1bgyELvKIM9xUYs/3m5HIc6mmQKBgQDKvaYx1nUTjikkkn88IC+TgnMGSBSDSKcxZd0IOUPC1ohtnB0x/IcH+mEBot8GEkn7CjnyvtbaBq3I+RxGpfrLzdzt8BH9tLxyrGA872iXfB8owRMpoOs0hDMRTT2gZPsXpNdQwDP4UvLqmsQPw0QO5id7gLnc5Rm6OSMcIaXx/QKBgQDBLvl3we7ZzN+PydXBY9AKnvAl9BeDFPgynsRXn0dYNuKDWR3PXF/IOLGraa7LHZ3L6WJY3fLRr5CMV+k8RjWo6aZMHRqFzsQGRW3ta7XczTO1yq6/ks6xHje/yQqeGdbJLD07StCwslA7JDukA5u0WuPkaozjRKLrN9ShiHDq8wKBgQCOxpoo1NekSuQsjkKuTBhVMHPiw5Y2kk60GgFbzkArETwIvP1Oe4F4m9n+9f1L4EtbUGtYyQ6zgiqWsuA33KHPLw3cPsncupBPzZcEsrEcpVuoLrhZA6tAU61HDPdOYm71yq+bfY/b3EaX8yAJ3cCrIWhCsHez2V+R5rUUFZow3QKBgQC81Sr7OfE8qrt49OTh5awNRbEemEuHUS8PZAwuTj5R50xg8fJmqDfkIi7hjCtU1f1Rvi7pCQL6nm9gD+qnhUWcd8+bJPOxChyouKMsaZXaYCcEszs/fcRWc2AxMtYTFtTRzlGILKhzn8k3FkLKHtDLafDLbK+M06Gg5PEOeK1PqwKBgQCn0r+NDE09ImX9PVymwomScpPRWC/SxVgmzx3mGDG0AaRqGjfa1hskqxwx8eRfT3exwvQ9dvYaPyfATyQZ0uEkg7bJ47Jfr7YHkcKMWM1+u8iVxN4mn6Kj3aSfy71iumPzm8J/9BZHX1cTLzXDi1OiP+mQs+UXqwXGfvx/UZHSgw==';

$merchantNo = '3203001000583086614';

//$url_base = "http://10.168.3.30:8080";
$urlBase = "https://qa-api.e-chain.net.cn";

$client = new EChainClient($urlBase,$merchantNo,$rsaPublic,$rsaPrivate);

//查询链上最新区块号
try{
  $blockNumber = $client->requestBlockNumber();
  echo '查询 blockNumber:' . $blockNumber . "\n";
}catch(Exception $e){
  echo "查询区块号异常：" . $e->getMessage(); 
}
echo "\n";

//查询交易收据
try{
  $txHash = "0x3ac02bbaca5e7e0adc05d0e36954c86ee39108d543542a49eed7420d445d2536";
  $receipt = $client->requestTransactionReceipt($txHash);
  echo '查询 transactionReceipt:' . json_encode($receipt) . "\n";
  if($receipt->result->statusOK){
    echo "交易上链成功\n";
  }else{
    echo "交易上链失败\n";
  }
}catch(Exception $e){
  echo "查询交易收据异常：" . $e->getMessage(); 
}
echo "\n";

//查询token所有者地址
try{
  $contractAddress = "0xc0f2254a5e506d6cda5e5ccd98ced32bd0e81609";
  $tokenId = "1000";
  $address = $client->requestTokenOwner($contractAddress,$tokenId);
  echo '查询 token所有者地址:' . "\n";
  echo "Owner地址：" . $address  . "\n";
}catch(Exception $e){
  echo "查询token所有者异常:" . $e->getMessage(); 
}
echo "\n";

//请求部署合约
try{
  $reqNo = "123";
  $owner = "0x90b8236912efdb64abbd59798375129c3594fe9f";
  $response = $client->requestDeployContract($reqNo,$owner);
  echo '部署合约:' . "\n";
  if($response){
    echo "合约地址：" . $response ."\n";
  }else{
    echo "请求失败\n";
  }
}catch(Exception $e){
  echo "部署合约异常：" . $e->getMessage(); 
}
echo "\n";

//请求发起一笔交易
try{
  //txHash,txSigned通过签名接口返回，参考SignDemo
  $txHash = "0x3ac02bbaca5e7e0adc05d0e36954c86ee39108d543542a49eed7420d445d2536";
  $txSigned = "0x1a1c2606636861696e30360667726f757030411044564e313132343839343038393630343334313535363636383839393339353934333631353930313239333432313236383932343438333431333637323338313536333931333939343838313634353938662a3078393964366264383836633130346261303637363634653738353961646134306633386265326335357d00004440c10f190000000000000000000000007f7cd0133ba23aca1140dd41180e07b9873c566400000000000000000000000000000000000000000000000000000000000000010b2d0000203ac02bbaca5e7e0adc05d0e36954c86ee39108d543542a49eed7420d445d25363d000041c2075f2495a49cfbb6dc4523568a22226dcbe86bfdff01da7bcdd04ae3e46bd6074436da0cfcb5712e1676e90d42d7709202c266ca1c89d8c947d1244a783389005001";
  $response = $client->requestSendTx($txHash,$txSigned,"");
  echo '发送交易:' . "\n";
  if($response){
    echo "发送成功\n";
  }else{
    echo "发送失败\n";
  }
}catch(Exception $e){
  echo "发送交易异常：" . $e->getMessage(); 
}


