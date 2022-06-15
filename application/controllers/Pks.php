<?php
require APPPATH.'/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Pks extends REST_Controller {
     public function __construct()
     {
         parent::__construct();
           $this->load->library('validatejwt');
           $this->load->model('model_pks');
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
               $response['total']       = $this->model_pks->totalRows();
               $response['data']        = $this->model_pks->data($data, $limit);
               $response['status']      = true;
               $response['message']     = 'Ok'; 
          }else{
               $response['message'] = 'Page is required';
          }

          $this->response($response, 200);
     }
}
?>