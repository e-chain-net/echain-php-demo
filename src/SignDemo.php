<?php

namespace EChainDemo;

require_once __DIR__ . '/../vendor/autoload.php';

use EChain\SignSDK;
use EChain\Account;

$sdk = SignSDK::getInstance();

$contractOwner = new Account("0xa7cc439fd510bcec39c8bfe6d3ab524e023dc340","381e852f542a72bb5f56f501a83260b814a14b5836821cc720b9e0a4eb410b68");
$tokenOwner1 = new Account("0x0c7ebf03fe7a61921ea4c93393c364859f3c2a3e","9f49267bed433fa1f298aedd81ba4bb3f73622f94b40e6e50d1190f25cca0b27");
$tokenOwner2 = new Account("0x90b8236912efdb64abbd59798375129c3594fe9f","3a34648b58b9c4d3a2e5811c223c34b24db2bca290361f20c9ec1c51f371b277");
$contractAddress = "0x0c7ebf03fe7a61921ea4c93393c364859f3c2a3e";
$tokenId = 1000;
$blockNumber = 100000;

function testAccount(){
    global $sdk;
    $account = $sdk->generateAccount();
    echo "address:" . $account->address . "\n";
    echo "private:" . $account->private . "\n\n";
}

function testSignMint(){
    global $sdk,$contractAddress,$tokenId,$contractOwner,$tokenOwner1,$blockNumber;
    $toAddress = $tokenOwner1->address;
    
    $signRes = $sdk->signMint($toAddress,$tokenId,$contractAddress,$contractOwner->private,$blockNumber);
    echo "Mint txHash:" . $signRes->txHash . "\n";
    echo "Mint signed:" . $signRes->signed . "\n\n";
}

function testSignTransfer(){
    global $sdk,$contractAddress,$tokenId,$tokenOwner2,$tokenOwner1,$blockNumber;
    $fromAddress = $tokenOwner1->address;
    $toAddress = $tokenOwner2->address;
    $signRes = $sdk->signTransferFrom($fromAddress,$toAddress,$tokenId,$contractAddress,$tokenOwner1->private,$blockNumber);
    echo "Transfer txHash:" . $signRes->txHash . "\n";
    echo "Transfer signed:" . $signRes->signed . "\n\n";
}

function testSignBurn(){
    global $sdk,$contractAddress,$tokenId,$tokenOwner2,$blockNumber;
    $signRes = $sdk->signBurn($tokenId,$contractAddress,$tokenOwner2->private,$blockNumber);
    echo "Burn txHash:" . $signRes->txHash . "\n";
    echo "Burn signed:" . $signRes->signed . "\n\n";
}

testAccount();
testSignMint();
testSignTransfer();
testSignBurn();