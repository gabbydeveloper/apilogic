<?php


require APPPATH . '/libraries/rest/REST_Controller.php';
require APPPATH . '/libraries/rest/Format.php';

use Restserver\Libraries\REST_Controller;

class Service extends REST_Controller
{
  public function __construct($config = 'rest')
  {
    parent::__construct($config);
    //Llamada al modelo
    $this->load->model('Service_model', 'model');
  }

  /********************************************************************************
   *  HEROES
   ********************************************************************************/

  public function heroes_get()
  {
    $this->load->helper('url');
    $query = $this->input->get('q');
    $id = $this->uri->segment(3);

    if (empty($id))
    {
      if (empty($query))
      {
        $data = $this->model->getHeroes();
        if ($data)
        {
          $this->response($data, 200);
        }
        else
        {
          $this->response(array('error' => 'Datos no encontrados'), 404);
        }
      }
      else
      {
        $limit = $this->input->get('limit');
        $data = $this->model->getHeroesByQuery($query, $limit);
        if ($data)
        {
          $this->response($data, 200);
        }
        else
        {
          $this->response(array(), 200);
        }
      }
    }
    else
    {
      $data = $this->model->getHeroById($id);
      if ($data)
      {
        $this->response($data, 200);
      }
      else
      {
        $this->response(array('error' => 'Datos no encontrados'), 404);
      }
    }
  }

  public function heroes_post()
  {
    $data = $this->post();

    if (!empty($data))
    {
      $result = $this->model->saveHero($data);
      if ($result)
        $this->response($result, 200);
      else
        $this->response(array('error' => 'Datos no grabados'), 404);
    }
    else
      $this->response(array('error' => 'Datos no encontrados para grabar'), 404);

  }

  public function heroes_patch()
  {
    $stream = $this->input->raw_input_stream;
    $data = json_decode($stream, true);

    if (!empty($data))
    {
      $result = $this->model->updateHero($data);
      if ($result)
        $this->response($result, 200);
      else
        $this->response(array('error' => 'Datos no grabados'), 404);
    }
    else
      $this->response(array('error' => 'Datos no encontrados para grabar'), 404);
  }

  public function heroes_delete()
  {
    $this->load->helper('url');
    $id = $this->uri->segment(3);
    if (!empty($id))
    {
      $result = $this->model->deleteHero($id);
      if ($result)
        $this->response($result, 200);
      else
        $this->response(array('error' => 'Datos no grabados'), 404);
    }
    else
      $this->response(array('error' => 'Datos no encontrados'), 404);
  }

  /********************************************************************************
   *  TASK-O
   ********************************************************************************/

  public function projects_get()
  {
    $this->load->helper('url');
    $query = $this->input->get('q');
    $id = $this->uri->segment(3);

    if (empty($id))
    {
      $data = $this->model->getProjects();
      if ($data)
      {
        $this->response($data, 200);
      }
      else
      {
        $this->response(array('error' => 'Data not found'), 404);
      }
    }
    else
    {
      $data = $this->model->getProjectById($id);
      if ($data)
      {
        $this->response($data, 200);
      }
      else
      {
        $this->response(array('error' => 'Data not found'), 404);
      }
    }
  }

  public function departments_get()
  {
    $data = $this->model->getDepartments();
    if ($data)
    {
      $this->response($data, 200);
    }
    else
    {
      $this->response(array('error' => 'Data not found'), 404);
    }
  }

  public function projects_post()
  {
    $data = $this->post();

    if (!empty($data))
    {
      $result = $this->model->saveProject($data);
      if ($result)
        $this->response($result, 200);
      else
        $this->response(array('error' => 'Data not found'), 404);
    }
    else
      $this->response(array('error' => 'Data not found to save'), 404);

  }

  public function users_get()
  {
    $token = $this->input->get('tkn');

    $data = $this->model->getUsers($token);
    if ($data)
    {
      $this->response($data, 200);
    }
    else
    {
      $this->response(array('error' => 'Data not found'), 404);
    }
  }

  public function login_get()
  {
    // Obtener parámetros de la URL
    $user = $this->input->get('usr');
    $pwd = $this->input->get('pwd');

    $data = $this->model->getLogin($user, $pwd);
    if ($data)
    {
      $this->response($data, 200);
    }
    else
    {
      $this->response(array('error' => 'Data not found'), 404);
    }
  }

  /********************************************************************************
   *  FILE UPLOAD
   ********************************************************************************/

  public function saveFile($file, $nameFile)
  {
    $rand = rand(1, 150);
    $config['upload_path'] = '../server_app/files/';
    $config['max_size'] = 0;
    $config['file_name'] = $nameFile . $rand;
    $config['allowed_types'] = 'pdf';

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload($file))
    {
      $error = strip_tags($this->upload->display_errors());
      h_console_log('error', $error);
      return false;
    }
    else
      return true;
  }

  public function envia_datos_post()
  {
    $name = $this->post('name');
    $lastName = $this->post('last_name');

    h_console_log('datos', $name . ' ' . $lastName);

    $correcto = $this->saveFile('file', 'rol-socio');

    if (!$correcto)
      $this->response(array('success' => false, 'error' => 'Error en la subida del archivo'), 400);
    else
      $this->response(null, 200);

  }

  /********************************************************************************
   *  COUNTRIES
   ********************************************************************************/

  public function region_get()
  {
    $data = $this->model->getContinentes();
    if ($data)
    {
      $this->response($data, 200);
    }
    else
    {
      $this->response(array('error' => 'Datos no encontrados'), 404);
    }
  }

  public function countries_get()
  {
    $idContinente = $this->input->get('continente');

    if (empty($idContinente))
    {
      $data = $this->model->getPaises();
      if ($data)
      {
        $this->response($data, 200);
      }
      else
      {
        $this->response(array('error' => 'Datos no encontrados'), 404);
      }
    }
    else
    {
      $data = $this->model->getPaisesByContinente($idContinente);
      if ($data)
      {
        $this->response($data, 200);
      }
      else
      {
        $this->response(array(), 200);
      }
    }

  }

  /********************************************************************************
   *  BUDGET BUDDY
   ********************************************************************************/

  public function loginbud_get()
  {
    // Obtener parámetros de la URL
    $user = $this->input->get('usr');
    $pwd = $this->input->get('pwd');

    $data = $this->model->getLoginBud($user, $pwd);
    if ($data)
    {
      $this->response($data, 200);
    }
    else
    {
      $this->response(array('error' => 'Datos no encontrados'), 404);
    }
  }

  public function userbud_get()
  {
    $token = $this->input->get('tkn');

    $data = $this->model->getUserByToken($token);
    if ($data)
    {
      $this->response($data, 200);
    }
    else
    {
      $this->response(array('error' => 'Datos no encontrados'), 404);
    }
  }

  public function tipos_get()
  {
    $usuario = $this->input->get('usuario');
    $data = $this->model->getTipos($usuario);
    if ($data)
    {
      $this->response($data, 200);
    }
    else
    {
      $this->response(array('error' => 'Datos no encontrados'), 404);
    }
  }

  public function saveform_post()
  {
    $idForma = $this->input->post('idForma');
    $data = $this->input->post('data');
    $usuario = $this->input->post('usuario');
    if ($data)
    {
      $res = $this->model->saveForm($usuario, $idForma, json_decode($data, true));
      $correcto = $res['success'];
      if ($correcto)
        $this->response(array('newId' => $res['newId']), 200);
      else
        $this->response(array('error' => 'Ha ocurrido un error'), 404);
    }
    else
    {
      $this->response(array('error' => 'Datos no encontrados'), 404);
    }
  }

  public function deleterow_post()
  {
    $idGrid = $this->input->post('idGrid');
    $data = $this->input->post('data');
    $usuario = $this->input->post('usuario');
    if ($data)
    {
      $res = $this->model->deteRow($usuario, $idGrid, json_decode($data, true));
      $correcto = $res['success'];
      if ($correcto)
        $this->response(array('total' => $res['total']), 200);
      else
        $this->response(array('error' => 'Ha ocurrido un error'), 404);
    }
    else
    {
      $this->response(array('error' => 'Ha ocurrido un error'), 404);
    }
  }

}

