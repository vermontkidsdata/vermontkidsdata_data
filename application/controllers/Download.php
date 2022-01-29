<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
		// Force SSL
		//$this->force_ssl();
	
		// Form and URL helpers always loaded (just for convenience)
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('download');
	}

	/**
	 * Controller for downloading ACS reports. Usage is
	 */
	public function index()
	{
		if( $this->require_role('admin') )
		{
			$this->load->view('admin/download/index');
		}
	}

	// http://localhost/census.gov/admin/download/view/b17001_5_year_poverty_age_sex
	public function view($reportName){
		if( $this->require_role('admin') ) {
			$this->load->model('Download_model');

			$fileName = $reportName . ".csv";

			$this->Download_model->download($fileName, "application/csv", $reportName);
		}
	}
	
}
