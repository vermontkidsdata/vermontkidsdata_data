<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visualization extends MY_Controller {
	
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
	 * Controller for managing Google visualizations.
	 */
	public function index()
	{
		//if( $this->require_role('admin') )
		//{
			$this->load->model('Visualization_model');
			$data = array();
			$this->load->view('admin/visualization/index', $data);
		//}
	}
	
	public function chart($id = null)
	{
		//if( $this->require_role('admin') )
		//{
		$this->load->model('Chart_model');
		$data = array();
		if($id != null){
			$chart = $this->Chart_model->getChart($id);
			$data['chart'] = $chart;
			
			if($chart->chart_type == 'line'){
				$rows = $this->Chart_model->getChartRows($chart->id);
				print_r($rows); exit;
			}
			
		}
		$this->load->view('admin/visualization/chart', $data);
		//}
	}
	
	
	
}
