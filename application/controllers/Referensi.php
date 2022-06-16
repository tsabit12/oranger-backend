<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Referensi extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		 $this->load->model(['model_referensi']);
	}

	public function berkas_get()
	{
		 $res['status']  = false;
		 $res['message'] = 'Internal server error';
		 $res['code']    = 500;

		try
		{
			 $data           = $this->model_referensi->berkas();
			 $res['status']  = true;
			 $res['message'] = 'oke';
			 $res['code']    = 200;
			 $res['result']  = $data;
		}
		catch (\Throwable $th)
		{
			 $res['message'] = 'Data tidak ditemukan';
			 $res['code']    = 404;
		}

		 $this->response($res, 200);
	}

	public function office_get()
	{
		 $res['status']  = false;
		 $res['message'] = 'Internal server error';
		 $res['code']    = 500;

		try
		{
			 $data           = $this->model_referensi->kantor();
			 $arr            = [];
			 $res['status']  = true;
			 $res['message'] = 'oke';
			 $res['code']    = 200;

			foreach ($data as $key => $item)
			{
				if (array_key_exists('wilayah', $item))
				{
					 $arr[str_replace(' ', '_', $item['wilayah'])][] = $item;
				}
			}

			 ksort($arr, SORT_NUMERIC);

			 $res['result'] = $arr;
		}
		catch (\Throwable $th)
		{
			 $res['message'] = 'Data tidak ditemukan';
			 $res['code']    = 404;
		}

		 $this->response($res, 200);
	}

	public function berkas_put(){
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
          }else{
			$res['status']  = false;
			$res['message'] = 'Internal server error';

			$data = $this->put();
			if(!isset($data['berkasid'])){
				$res['message'] = 'Berkas ID is required';
			}else{
				$config = array(
					array('field' => 'berkasid', 'label' => 'Berkas ID', 'rules' => 'required|max_length[3]|min_length[3]'),
					array('field' => 'keterangan', 'label' => 'Deskripsi', 'rules' => 'required'),
					array('field' => 'with_file', 'label' => 'File', 'rules' => 'required|integer')
				);

				$this->form_validation->set_data($data);
				$this->form_validation->set_rules($config);

				if($this->form_validation->run() === FALSE){
					$msg_arr            = $this->form_validation->error_array();
					$keys                = array_keys($msg_arr);
					$res['message'] = $msg_arr[$keys[0]];
				}else{
					$upd = $this->model_referensi->updateBerkas($data);
					if($upd['success']){
						$res['status'] = true;
						$res['message'] = "Ok";
					}else{
						$res['message'] = "UPDATE DATA GAGAL";
					}
				}
			}

			$this->response($res, 200);
		}
	}
}
?>
