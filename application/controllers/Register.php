<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Register extends REST_Controller {
     public function __construct()
	{
		parent::__construct();
		  $this->load->model(['model_register']);
	}

     public function index_post(){
          $response['status'] = false;
          $response['message'] = "Internal server error";

          
          $body = $this->post();

          if(isset($body['nik'])){
               $config = array(
                    array('field' => 'fullname', 'label' => 'Fullname', 'rules' => 'required|max_length[70]'),
                    array('field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email|is_unique[t_mitra.email]'),
                    array('field' => 'phone', 'label' => 'phone', 'rules' => 'required|max_length[15]|integer'),
                    array('field' => 'nik', 'label' => 'NIK', 'rules' => 'required|max_length[19]|integer'),
                    array('field' => "tempatlahir", 'label' => 'Birthplace', 'required|max_length[30]'),
                    array('field' => "tanggallahir", 'label' => 'Birthday', 'required|max_length[10]'),
                    array('field' => "npwp", 'label' => 'NPWP', 'required|max_length[16]'),
                    array('field' => 'alamat', 'label' => 'Alamat KTP', 'required'),
                    array('field' => 'alamatdomisili', 'label' => 'Alamat Domisili', 'required'),
                    array('field' => 'status', 'label' => 'Status', 'required'),
                    array('field' => 'agama', 'label' => 'Status', 'required|max_length[30]'),
                    array('field' => 'kantor', 'label' => 'Kantor', 'required|max_length[8]'),
                    array('field' => 'gender', 'label' => 'Jenis kelamin', 'required|max_length[20]')
               );
               
               $this->form_validation->set_data($body);
               $this->form_validation->set_rules($config);

               if($this->form_validation->run() === FALSE){
                    $msg_arr            = $this->form_validation->error_array();
                    $keys                = array_keys($msg_arr);
                    $response['message'] = $msg_arr[$keys[0]];
               }else{
                    $username = $this->model_register->getLastId('560');
                    $config = array(
					'upload_path'   => './assets/berkas',
					'allowed_types' => 'jpg|pdf|png|jpeg',
					'max_size'     => 1500000 //in bites
					// 'encrypt_name' => TRUE     
				);
				
				$this->load->library('upload');

				$images = array();
				$files = $_FILES['berkas'];
                    if (isset($files)) {
					$datatable_berkas = array();

                         foreach ($files['name'] as $key => $image) {
                              $_FILES['images[]']['name']= $files['name'][$key];
						$_FILES['images[]']['type']= $files['type'][$key];
						$_FILES['images[]']['tmp_name']= $files['tmp_name'][$key];
						$_FILES['images[]']['error']= $files['error'][$key];
						$_FILES['images[]']['size']= $files['size'][$key];
	
						$fileName = $key.$username."_".$body['nik'];
                              $images[] = $fileName;
						$config['file_name'] = $fileName;

                              $this->upload->initialize($config);
                              if ($this->upload->do_upload('images[]')) {
                                   $dataInfo = $this->upload->data();
                                   $datatable_berkas[] = array(
                                        'berkasid' => $key,
                                        'file_name' => $dataInfo['file_name'],
                                        'username' => $username
                                   );
                              }else{
                                   $err = $this->upload->display_errors();
                              }
                         }

                         $addmitra = $this->model_register->addmitra($body, $datatable_berkas, $username);
                         if($addmitra['success']){
                              $response['status'] = true;
                              $response['message'] = "ok";
                         }else{
                              $response['message'] = $addmitra['msg'];
                         }
                    }else{
                         $response['message'] = "Empty berkas, please add file before submit";    
                    }
               }
          }else{
               $response['message'] = "Nik is required";
          }

          $this->response($response, 200);
     }
}

?>