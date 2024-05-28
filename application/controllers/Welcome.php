<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Service_model");
  }

  public function index()
  {
    $data = $this->Service_model->getHeroes();
    if (!empty($data))
    {
      foreach ($data as $d)
      {
        echo $d['superhero'] . '<br/>';
      }
    }
  }
}
