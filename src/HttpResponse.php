<?php

namespace EChainDemo;

class HttpReponse{
  public $response;
  public $error;

  public function __construct($response,$error){
    $this->response = $response;
    $this->error = $error;
  }
}