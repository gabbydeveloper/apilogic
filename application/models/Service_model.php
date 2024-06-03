<?php

class Service_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();

    // Cargar la segunda base de datos
    $this->db2 = $this->load->database('second', TRUE);
  }


  /********************************************************************************
   *  HEROES
   ********************************************************************************/

  public function getHeroes()
  {
    return $this->db->get("hero")->result_array();
  }

  public function getHeroById($id)
  {
    return $this->db->where('id', $id)->get("hero")->row_array();
  }

  public function getHeroesByQuery($query, $limit)
  {
    return $this->db->where("superhero LIKE '%$query%'")
                    ->limit($limit)
                    ->get("hero")->result_array();
  }

  public function saveHero($data)
  {
    $data['id'] = $this->generateId(8);
    $result = $this->db->insert('hero', $data);
    if (!$result) return false;
    return $data;
  }

  private function generateId($length)
  {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = strlen($characters);
    $code = '';

    for ($i = 0; $i < $length; $i++)
    {
      $code .= $characters[rand(0, $num - 1)];
    }

    return $code;

  }

  public function updateHero($data)
  {
    $result = $this->db->where('id', $data['id'])->update('hero', $data);
    if (!$result) return false;
    return $data;
  }

  public function deleteHero($id)
  {
    return $this->db->where('id', $id)->delete('hero');
  }

  /********************************************************************************
   *  TASK-O
   ********************************************************************************/

  public function getProjects($id = '')
  {
    $sq1 = "(SELECT count(*) 
               FROM task t
              WHERE t.id_project = p.id_project
                AND t.state_task = 'END') AS xxx_ended_tasks";

    $sq2 = "(SELECT count(*) 
               FROM task t
              WHERE t.id_project = p.id_project) AS xxx_total_tasks";

    if (!empty($id))
      $this->db2->where('p.id_project', $id);

    $data = $this->db2->select("p.id_project,
                               p.name_project, 
                               p.id_department, 
                               p.duration_months,
                               p.state_project,
                               0 AS xxx_percentage,
                               d.name_department AS xxx_department,
                               $sq1, $sq2")
                     ->join('department d', 'd.id_department = p.id_department')
                     ->order_by('p.id_project')
                     ->get('project p')->result_array();

    if (!empty($data))
    {
      foreach ($data as &$d)
      {
        if ($d['xxx_total_tasks'] > 0)
          $d['xxx_percentage'] = $d['xxx_ended_tasks'] / $d['xxx_total_tasks'] * 100;
        else
          $d['xxx_percentage'] = 0;
      }
    }

    if (!empty($id)) $data = $data[0];

    return $data;
  }

  public function getProjectById($id)
  {
    return $this->getProjects($id);
  }

  public function getDepartments()
  {
    return $this->db2->select('id_department, name_department')
                    ->get('department')->result_array();
  }

  public function saveProject($data)
  {
    $id = $data['id_project'];
    if ($id == '0' or empty($id))
    {
      unset($data['id_project']);
      $result = $this->db2->insert('project', $data);
      if (!$result) return false;
      $id = $this->db2->insert_id();
      $data['id_project'] = $id;
    }
    else
    {
      $result = $this->db2->where('id_project', $data['id_project'])->update('project', $data);
      if (!$result) return false;
    }
    return $data;
  }

  public function getUsers($token)
  {
    if (!empty($token))
      $this->db2->where('token', $token);

    $data = $this->db2->select('id_executor AS id_user,
                                name_executor AS name_user,
                                user_name AS user,
                                token')
                     ->get('executor')->result_array();

    if (!empty($token))
      $data = $data[0];

    return $data;
  }

  private function generateToken($length)
  {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = strlen($characters);
    $code = '';

    for ($i = 0; $i < $length; $i++)
    {
      $code .= $characters[rand(0, $num - 1)];
    }

    return $code;

  }

  public function getLogin($user, $pwd)
  {
    $data = $this->db2->select('id_executor AS id_user,
                              name_executor AS name_user,
                              user_name AS user,
                              token')
                     ->where('user_name', $user)
                     ->where("password = md5('$pwd')")
                     ->get('executor')->row_array();

    if (empty($data))
      return false;

    $part1 = $this->generateToken(6);
    $part2 = $this->generateToken(6);
    $part3 = $this->generateToken(6);
    $token = $part1 . '.' . $part2 . '.' . $part3;

    $this->db2->where('id_executor', $data['id_user'])
             ->set('token', $token)
             ->update('executor');

    $data['token'] = $token;

    return $data;

  }

  /********************************************************************************
   *  COUNTRIES
   ********************************************************************************/

  public function getContinentes()
  {
    return $this->db->get("continente")->result_array();
  }

  public function getPaises()
  {
    return $this->db->get("pais")->result_array();
  }

  public function getPaisesByContinente($idContinente)
  {
    return $this->db->where("id_continente = $idContinente")
                    ->get("pais")->result_array();
  }

}
