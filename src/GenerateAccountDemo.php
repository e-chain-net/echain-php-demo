<?php

namespace EChainDemo;

require_once __DIR__ . '/../vendor/autoload.php';

use EChain\SignSDK;
use EChain\Account;

//Usage:    php GenerateAccountDemo.php

$sdk = SignSDK::getInstance();

function testAccount(){
    global $sdk;
    $account = $sdk->generateAccount();
    echo "address:" . $account->address . "\n";
    echo "private:" . $account->private . "\n\n";
}

testAccount();