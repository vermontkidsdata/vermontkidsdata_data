<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Census extends MY_Controller {

	/**
	 * Main Controller for managing data and assembling the How are Vermont's Young Children report
	 */
	public function index()
	{
		
		if( $this->require_role('admin') )
		{
			$data = array();
			$this->load->model('Census_model');
			
			$userId = $this->auth_user_id; 
			$data['userId'] = $userId;
			//get geography maps that the user has uploaded
			$geoMaps = $this->Census_model->getGeographyMapsForUser($userId);
			$data['geoMaps'] = $geoMaps;
			
			$this->load->view('census/index',$data);
		}
		
	}
	
	public function geography_map(){
	    if( $this->require_role('admin') )
	    {
	    $this->load->model('Census_model');
	    $data = array();
	    $this->load->view('census/geography-map', $data);
	    }
	}

	public function report($id = null){

		$this->load->model('Census_model');
		
		$report = $this->Census_model->getReport($id);
		$data['report'] = $report;
		
		$tableData = $this->Census_model->getReportTableData($id);
		$data['tableData'] = $tableData;
		
		//print_r($data); exit;
		$this->load->view('census/census-report', $data);
	}

	public function reports(){

		$this->load->model('Census_model');
		$reports = $this->Census_model->getReports();
		$data['reports'] = $reports;
		$this->load->view('census/census-reports-index', $data);

	}
	
	public function dataset($id = null)
	{
		$data = array();
		$this->load->model('Havyc_model');
		$this->load->model('HavycChart_model');
	
		$dataset = $this->Havyc_model->getDataset($id);
		$data['dataset'] = $dataset;
		
		
		
		$datasetdata = $this->Havyc_model->getExternalDatasetData($id);
		$data['datasetdata'] = $datasetdata;
		
		//print_r($datasetdata); exit;
		
		$columns = explode(',', $dataset->columns);
		$data['columns'] = $columns;
		
		$ageGroups = $this->Havyc_model->getAgeGroups();
		$data['ageGroups'] = $ageGroups;
		
		$geographies = $this->Havyc_model->getGeographies();
		$data['geographies'] = $geographies;
		
		$race = $this->Havyc_model->getRace();
		$data['race'] = $race;
		
		$years = $this->Havyc_model->getYears();
		$data['years'] = $years;
		
		$schoolyears = $this->Havyc_model->getSchoolYears();
		$data['schoolyears'] = $schoolyears;
		
		$householdtypes = $this->Havyc_model->getHouseholdTypes();
		$data['householdtypes'] = $householdtypes ;
		
		//get the charts for the dataset
		
		$charts = $this->HavycChart_model->getChartsForDataset($id);
		$dataSetCharts = array();
		$datasetTables = array();
		//print_r($charts); exit;
		foreach($charts as $c){
			
			if($c->chart_type == 'line'){ 
				$tmpData = $this->HavycChart_model->getLineChart($c->id);
				$dataSetCharts[] = $tmpData;
			}
			
			if($c->chart_type == 'pie' || $c->chart_type == 'doughnut'){
				$tmpData = $this->HavycChart_model->getPieChart($c->id);
				$dataSetCharts[] = $tmpData;
			}
			
			if($c->chart_type == 'horizontal bar' || $c->chart_type == 'stacked bar'  || $c->chart_type == 'bar' ){
				$tmpData = $this->HavycChart_model->getBarChart($c->id);
				$dataSetCharts[] = $tmpData;
			}
			
		}
		
		$tables = $this->HavycChart_model->getTablesForDataset($id);
		foreach ($tables as $t){
			$tmpData = $this->HavycChart_model->getTableData($t->id);
			$datasetTables[] = $tmpData;
		}
		
		$data['datasetCharts'] = $dataSetCharts;
		$data['datasetTables'] = $datasetTables;
		
		
		//print_r($dataSetCharts); exit;
		//print_r(json_encode($chartData, JSON_NUMERIC_CHECK));exit;
	
		$this->load->view('datasets/dataset',$data);
	}
	
	
}
