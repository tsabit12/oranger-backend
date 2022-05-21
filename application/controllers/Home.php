<?php
require APPPATH.'/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Home extends  REST_Controller{
     public function __construct()
    {
        parent::__construct();
          $this->load->library('validatejwt');
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
          $res = array('status' => 'okkekkk');
          $this->response($res, 200);
     }
}
?>