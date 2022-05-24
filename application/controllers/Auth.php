<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
use Firebase\JWT\JWT;

class Auth extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		  $this->load->model(['model_auth']);
	}

	public function index_post()
	{
		 $response['status']  = false;
		 $response['code']    = 500;
		 $response['message'] = 'Internal server error';

		 $body = $this->post();
		if (! isset($body['username']))
		{
			  $response['code']    = 402;
			  $response['message'] = 'Username field is required';
		}
		else
		{
			  $config = [
				  [
					  'field' => 'username',
					  'label' => 'Username',
					  'rules' => 'required|max_length[20]',
				  ],
				  [
					  'field' => 'password',
					  'label' => 'Password',
					  'rules' => 'required|max_length[50]',
				  ],
			  ];

			  $this->form_validation->set_rules($config);
			  $this->form_validation->set_data($body);

			  if ($this->form_validation->run() === true)
			  {
				   $login = $this->model_auth->login($body);
				  if ($login['success'])
				  {
					  $response['message'] = 'Oke';
					  $response['status']  = true;
					  $response['code']    = 200;
					  $response['token']   = $this->getToken($login['data']);
				  }
				  else
				  {
					  $response['message'] = 'Invalid username or password';
					  $response['code']    = 404;
				  }
			  }
			  else
			  {
				   $response['code'] = 403;

				   $msg_arr            = $this->form_validation->error_array();
				  $keys                = array_keys($msg_arr);
				  $response['message'] = $msg_arr[$keys[0]];
			  }
		}
		 $this->response($response, 200);
	}

	private function getToken($payload)
	{
		$key = getenv('TOKEN_SECRET');
		return JWT::encode($payload, $key, 'HS256');
	}
}

?>
