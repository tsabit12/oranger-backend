<?php
require APPPATH.'/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Kandidat extends  REST_Controller{
     public function __construct()
     {
         parent::__construct();
           $this->load->library('validatejwt');
           $this->load->model('model_kandidat');
           $validate = $this->validatejwt->cek_token();
           if($validate['isValid'] === FALSE){
                echo json_encode(
                     array(
                          "status" => false,
                          "message" => $validate['message']
                     )
                );
                die();
           }
     }

     public function index_get(){
          $response['status']      = false;
          $response['message']     = 'Internal server error';

          $data     = $this->get();
          $limit    = 10;

          if(isset($data['page'])){
               $response['total']       = $this->model_kandidat->totalRows();
               $response['data']        = $this->model_kandidat->data((int)$data['page'], $limit);
               $response['status']      = true;
               $response['message']     = 'Ok'; 
          }else{
               $response['message'] = 'Page is required';
          }

          $this->response($response, 200);
     }

     public function berkas_get(){
          $response['status']      = false;
          $response['message']     = 'Internal server error';

          $params = $this->get();

          if(isset($params['username'])){
               $data = $this->model_kandidat->berkas($params['username']);
               if($data['success']){
                    $response['status'] = true;
                    $response['message'] = "Ok";
                    $response['result'] = $data['data'];
               }
          }else{
               $response['message'] = 'Username is required';
          }

          $this->response($response, 200);
     }

     public function berkas_post(){
          $response['status']      = false;
          $response['message']     = 'Internal server error';

          $payload = $this->post();
          if(isset($payload['berkasid'])){
               $config = array(
                    array('field' => 'berkasid', 'label' => 'Berkas ID', 'rules' => 'required|max_length[3]'),
                    array('field' => 'nilai', 'label' => 'Nilai', 'rules' => 'required|integer'),
                    array('field' => 'username', 'label' => 'Username', 'rules' => 'required|max_length[30]'),
               );

               $this->form_validation->set_data($payload);
               $this->form_validation->set_rules($config);

               if($this->form_validation->run() === FALSE){
                    $msg_arr            = $this->form_validation->error_array();
                    $keys                = array_keys($msg_arr);
                    $response['message'] = $msg_arr[$keys[0]];
               }else{
                    $update = $this->model_kandidat->updateberkas($payload);
                    if($update['success']){
                         $response['status'] = true;
                         $response['message'] = "ok";
                    }else{
                         $response['message']     = 'Update failed';    
                    }
               }
          }else{
               $response['message']     = 'Berkas id is required';
          }

          $this->response($response, 200);
     }

     public function pks_post(){
          $response['status']      = false;
          $response['message']     = 'Internal server error';

          $payload = $this->post();
          if(isset($payload['username'])){
               $config = array(
                    array('field' => 'username', 'label' => 'Username', 'rules' => 'required|max_length[30]'),
                    array('field' => 'judul', 'label' => 'Judul', 'rules' => 'required|max_length[100]'),
                    array('field' => 'nopks', 'label' => 'Nomor PKS', 'rules' => 'required|max_length[70]'),
                    array('field' => 'mulai', 'label' => 'Tanggal Mulai', 'rules' => 'required|max_length[10]'),
                    array('field' => 'selesai', 'label' => 'Tanggal Selesai', 'rules' => 'required|max_length[10]'),
               );

               $this->form_validation->set_data($payload);
               $this->form_validation->set_rules($config);
               if($this->form_validation->run() === FALSE){
                    $msg_arr            = $this->form_validation->error_array();
                    $keys                = array_keys($msg_arr);
                    $response['message'] = $msg_arr[$keys[0]];
               }else{
                    $add = $this->model_kandidat->addpks($payload);
                    if($add['success']){
                         $response['status']      = true;
                         $response['message']     = 'Ok';    
                    }else{
                         $response['message']     = "INSERT FAILED";
                    }
               }
          }else{
               $response['message']     = 'Username id is required';
          }

          $this->response($response, 200);
     }

     public function review_post(){
          $response['status']      = false;
          $response['message']     = 'Internal server error';

          $payload = $this->post();
          if(isset($payload['username'])){
               $config = array(
                    array('field' => 'username', 'label' => 'Username', 'rules' => 'required|max_length[30]'),
                    array('field' => 'nilai', 'label' => 'nilai', 'rules' => 'required'),
                    array('field' => 'status', 'label' => 'Label', 'rules' => 'required|max_length[2]')
               );
               $this->form_validation->set_data($payload);
               $this->form_validation->set_rules($config);
               if($this->form_validation->run() === FALSE){
                    $msg_arr            = $this->form_validation->error_array();
                    $keys                = array_keys($msg_arr);
                    $response['message'] = $msg_arr[$keys[0]];
               }else{
                    $post = $this->model_kandidat->review($payload);
                    if($post['success']){
                         $response['status']      = true;
                         $response['message']     = 'Ok';    
                    }else{
                         $response['message']     = "UPDATE FAILED";
                    }
               }
          }else{
               $response['message']     = 'Username id is required';    
          }

          $this->response($response, 200);
     }
}

?>