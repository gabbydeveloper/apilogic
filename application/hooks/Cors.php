<?php

class Cors
{

  public function initCors()
  {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
    {
      // Pre-flight request. Detener la ejecución
      exit(0);
    }
  }

}
