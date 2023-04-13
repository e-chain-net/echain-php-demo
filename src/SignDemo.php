<?php

namespace EChainDemo;

require_once __DIR__ . '/../vendor/autoload.php';

use EChain\SignSDK;

$sdk = SignSDK::getInstance();

$private = "9f49267bed433fa1f298aedd81ba4bb3f73622f94b40e6e50d1190f25cca0b27";
$contractAddress = "0x0c7ebf03fe7a61921ea4c93393c364859f3c2a3e";
$tokenId = 1000;

function testAccount(){
    global $sdk;
    $account = $sdk->generateAccount();
    echo "address:" . $account->address . "\n";
    echo "private:" . $account->private . "\n\n";
}

function testSignMint(){
    $toAddress = "0x0c7ebf03fe7a61921ea4c93393c364859f3c2a3e";
    $blockNumber = 100000;
    global $sdk,$private,$contractAddress,$tokenId;
    $signRes = $sdk->signMint($toAddress,$tokenId,$contractAddress,$private,$blockNumber);
    echo "Mint txHash:" . $signRes->txHash . "\n";
    echo "Mint signed:" . $signRes->signed . "\n\n";
}

function testSignTransfer(){
    $fromAddress = "0xcDFC7406BeacF91ED425eade994CD0839d3FA9fD";
    $toAddress = "0x0c7ebf03fe7a61921ea4c93393c364859f3c2a3e";
    $blockNumber = 100000;
    global $sdk,$private,$contractAddress,$tokenId;
    $signRes = $sdk->signTransferFrom($fromAddress,$toAddress,$tokenId,$contractAddress,$private,$blockNumber);
    echo "Transfer txHash:" . $signRes->txHash . "\n";
    echo "Transfer signed:" . $signRes->signed . "\n\n";
}

function testSignBurn(){
    $blockNumber = 100000;
    global $sdk,$private,$contractAddress,$tokenId;
    $signRes = $sdk->signBurn($tokenId,$contractAddress,$private,$blockNumber);
    echo "Burn txHash:" . $signRes->txHash . "\n";
    echo "Burn signed:" . $signRes->signed . "\n\n";
}

testAccount();
testSignMint();
testSignTransfer();
testSignBurn();