<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {
	
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
	
	public function get() {
		//get saved user reports
		if( $this->require_role('admin') )
		{
			$this->load->model('Report_model');
			$reports = $this->Report_model->getReports();
			$data['reports'] = $reports;
			$this->load->view('admin/report/index', $data);
		}
	}
	
	public function index()
	{
		//if( $this->require_role('admin') )
		//{
			$this->load->model('Report_model');
			$reports = $this->Report_model->getReports();
			$data['reports'] = $reports;
			$this->load->view('admin/report/index', $data);
		//}
	}
	
	public function edit($reportId = null){
		
		$this->load->model('Report_model');
		$data = array();
		$reportYears = $this->Report_model->getReportYears();
		$data['years'] = $reportYears;
		if($reportId != null){
			$data['reportfunction'] = 'Edit';
			$report = $this->Report_model->getReport($reportId);
			$data['report'] = $report;
			
			$this->load->model('Reportgroup_model');
			$reportGroups = $this->Reportgroup_model->getReportGroups($reportId);
			$data['reportGroups'] = $reportGroups;
		
		} else {
			$data['reportfunction'] = 'Create';
		}
		
		$this->load->view('admin/report/edit',$data);
		
	}
	
	
	public function save(){
		
		$this->load->model('Report_model');
		//print_r($_POST);
		$data = array();
		foreach($_POST as $name => $val){
			$data[$name] = $val;
		}
		//print_r($data);
		$this->Report_model->createReport($data);
		$this->load->helper('url');
		redirect(base_url().'report');
		
		
	}
	
}
