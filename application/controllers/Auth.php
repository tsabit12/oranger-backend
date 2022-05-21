<?php
require APPPATH.'/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Auth extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
          $this->load->model(array('model_auth'));
    }

    public function index_get()
    {
         $this->response(array("status" => "oke"));
    }
}

?>