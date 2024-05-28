<?php

class Cors
{
  public function initCors()
  {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
    {
      // Pre-flight request. Detener la ejecución
      exit(0);
    }
  }

}
