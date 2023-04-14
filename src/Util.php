<?php

namespace EChainDemo;

use Unirest\Request;
use Unirest\Request\Body;

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
}