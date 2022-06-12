<?php

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
use Firebase\JWT\JWT;

class Pushtrx extends REST_Controller {
     public function index_post(){
          $response['status'] = false;
          $response['error'] = "Internal server error";
          
          $data = $this->post();
          if(!isset($data['values'])){
              $response['error'] = "Values body required";
          }else{
              $valueIsArray = $this->validateArray($data['values']);
              $response['inserted'] = count($data['values']);
              if($valueIsArray){
                  $insert = $this->insertDataAntaran($data['values']);
                  if($insert['success']){
                      $response['status'] = true;
                      $response['error'] = "";
                      $response['message'] = "Add transaction success";
                  }else{
                      $response['error'] = "Insert failed! Please check your values";
                  }
              }else{
                  $response['error'] = 'Values not in array';
              }
          }
     
          $this->response($response, 200);
      }
     
      private function validateArray($mixed){
          return is_array($mixed) || $mixed instanceof Traversable ? true : false;
      }

      private function insertDataAntaran($data){
          $result['success'] = true;
          $insert = array();
  
          foreach($data as $key){
              $insert[] = $key;
          }
  
          $this->db->db_debug = FALSE; 
          $add = $this->db->insert_batch('kinerja_antaran_kemitraan_pid_api', $insert);
  
          if($add){
              if($this->db->affected_rows() > 0){
                  $result['success'] = true;
              }
          }
          
          return $result;
      }
}    
?>