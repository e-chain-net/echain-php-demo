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
    echo "生成单个账户：\n";
    echo "address:" . $account->address . "\n";
    echo "private:" . $account->private . "\n\n";
}

testAccount();



//测试批量生成账户的性能
$start = Util::getMilliseconds();
$count = 10;
$accounts = Util::generateAccounts($sdk,$count);
for($i = 0; $i < $count; $i++){
    echo "第" . ($i+1) . "个address:" . $accounts[$i]->address . "\n";
    echo "第" . ($i+1) . "个private:" . $accounts[$i]->private . "\n\n";
}
$end = Util::getMilliseconds();

echo "Generate " . $count . " accounts time elapsed:" . ($end-$start) . "ms\n";