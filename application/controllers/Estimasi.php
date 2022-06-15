<?php
require APPPATH.'/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Estimasi extends REST_Controller {
     public function __construct()
     {
         parent::__construct();
           $this->load->library('validatejwt');
           $this->load->model('model_estimasi');
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

          if(isset($data['limit'])){
               $limit = $data['limit'];
          }

          if(isset($data['page'])){
               $config = array(
                    array('field' => 'page', 'label' => 'Page', 'rules' => 'integer|required'),
                    array('field' => 'tgl_awal', 'label' => 'tgl_awal', 'rules' => 'required|max_length[10]'),
                    array('field' => 'tgl_akhir', 'label' => 'tgl_akhir', 'rules' => 'required|max_length[10]'),
                    array('field' => 'regional', 'label' => 'regional', 'rules' => 'required|max_length[2]'),
                    array('field' => 'kprk', 'label' => 'kprk', 'rules' => 'required|max_length[10]'),
               );

               $this->form_validation->set_data($data);
               $this->form_validation->set_rules($config);

               if($this->form_validation->run() === FALSE){
                    $msg_arr            = $this->form_validation->error_array();
                    $keys                = array_keys($msg_arr);
                    $response['message'] = $msg_arr[$keys[0]];
               }else{
                    $response['total']       = $this->model_estimasi->getdata($data, null, 'count');
                    $response['data']        = $this->model_estimasi->getdata($data, $limit);
                    $response['status']      = true;
                    $response['message']     = 'Ok'; 
               }
          }else{
               $response['message'] = 'Page is required';
          }

          $this->response($response, 200);
     }
}
?>