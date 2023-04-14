<?php

namespace EChainDemo;

require_once __DIR__ . '/../vendor/autoload.php';

use EChain\SignSDK;
use EChainDemo\Util;

$sdk = SignSDK::getInstance();

$private = "9f49267bed433fa1f298aedd81ba4bb3f73622f94b40e6e50d1190f25cca0b27";
$contractAddress = "0x0c7ebf03fe7a61921ea4c93393c364859f3c2a3e";


function testSignMint($tokenId){
    $toAddress = "0x0c7ebf03fe7a61921ea4c93393c364859f3c2a3e";
    $blockNumber = 100000;
    global $sdk,$private,$contractAddress;
    $signRes = $sdk->signMint($toAddress,$tokenId,$contractAddress,$private,$blockNumber);
}

function testSignTransfer($tokenId){
    $fromAddress = "0xcDFC7406BeacF91ED425eade994CD0839d3FA9fD";
    $toAddress = "0x0c7ebf03fe7a61921ea4c93393c364859f3c2a3e";
    $blockNumber = 100000;
    global $sdk,$private,$contractAddress;
    $signRes = $sdk->signTransferFrom($fromAddress,$toAddress,$tokenId,$contractAddress,$private,$blockNumber);
}

$start = Util::getMilliseconds();
for($i = 0; $i < 10000; $i++){
   testSignMint($i+1);
}
$end = Util::getMilliseconds();

echo "Sign for 10000 mint time elapsed:" . ($end-$start) . "ms\n";

$start = Util::getMilliseconds();
for($i = 0; $i < 10000; $i++){
    testSignTransfer($i+1);
}
$end = Util::getMilliseconds();
echo "Sign for 10000 transfer time elapsed:" . ($end-$start) . "ms\n";

