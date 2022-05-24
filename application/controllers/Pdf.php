<?php
error_reporting(0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');

class Pdf extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('generatepdf');
		$this->load->model(['model_invoice', 'model_auth']);
	}

	public function invoice()
	{
		$data = $this->input->get();
		$html = $this->load->view('404', [], true);

		if (isset($data['token']))
		{
			$userid       = isset($data['userid']) ? $data['userid'] : null;
			$isValidToken = $this->model_auth->validateToken($data['token'], $userid);
			if ($isValidToken)
			{
				if (isset($data['invoiceid']))
				{
					$getdata = $this->model_invoice->pdfreport($data);
					if ($getdata['success'])
					{
						$data['record'] = $getdata['data'][0];
						$html           = $this->load->view('invoice', $data, true);
					}
				}
			}
		}

		$this->generatepdf->generate($html, 'filesaya', 'landscape');
	}

	public function resi()
	{
		$params = $this->input->get();
		$html   = $this->load->view('404', [], true);

		if (isset($params['data']))
		{
			$data['record'] = json_decode($params['data'], true);
			$html           = $this->load->view('resi', $data, true);
		}

		$this->generatepdf->generate($html, 'resi', 'potrait');
	}

	public function posaja()
	{
		$params         = $this->input->get();
		$data['record'] = $params;
		$html           = $this->load->view('posaja', $data, true);

		$this->generatepdf->generate($html, 'resi', 'potrait');
	}
}

?>
