<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Authorization, Content-Length, X-API-KEY');

class Credential extends CI_Controller
{
	public function __construct()
	{
		 parent::__construct();
		 $this->load->model(['model_credential']);
	}

	public function index()
	{
		 $response['status']  = false;
		 $response['message'] = 'Key not found';

		 $result = $this->model_credential->getkey();
		if ($result['success'])
		{
			 $response['status']  = true;
			 $response['message'] = 'Oke';

			 /* why X-API-KEY? --> harus sama dengan yang
			 ada di application/config/rest.php LINE 380 */

			 $response['result']['X-API-KEY'] = $result['data'];
			 $response['result']['username']  = 'orangeraja'; //rest.php LINE 214
			 $response['result']['password']  = '0r@ng3334'; //rest.php LINE 214
		}

		 echo json_encode($response);
	}
}
?>
