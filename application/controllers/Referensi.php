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
}
?>
