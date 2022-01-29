<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
		// Force SSL
		//$this->force_ssl();
	
		// Form and URL helpers always loaded (just for convenience)
		$this->load->helper('url');
		$this->load->helper('form');
	}

	/**
	 * Controller for managing ACS reports.
	 */
	
	public function index() {
		//get saved user reports
		if( $this->require_role('admin') )
		{
			$this->load->model('Reports_model');
			$reports = $this->Reports_model->getReports();
			$data['reports'] = $reports;
			$this->load->view('user/reports-index', $data);
		}
	}
	
	
	
}
