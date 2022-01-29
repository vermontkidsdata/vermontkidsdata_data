<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datasets extends CI_Controller {

	/**
	 * Main Controller for managing data and assembling the How are Vermont's Young Children report
	 */
	public function index()
	{
		$data = array();
		$this->load->model('Havyc_model');
		
		$areasOfFocus = $this->Havyc_model->getAreasOfFocus();
		$data['areasOfFocus'] = $areasOfFocus;
		
		$datasets= $this->Havyc_model->getDatasets(null);
		$data['dataset'] = $datasets;
		
		//print_r($datasets); exit;
		
		$this->load->view('datasets/datasets',$data);
	}
	
	public function gaps(){
	    $data = array();
	    $this->load->model('Datacatalog_model');
	    //$dataElements = $this->Datacatalog_model->getElements();
	    $dataElements = array();
	    $data['dataElements'] = $dataElements;
	    $this->load->view('datasets/gaps',$data);
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
		//print_r($dataSetCharts); exit;
		
		$tables = $this->HavycChart_model->getTablesForDataset($id);
		//print_r($tables);
		foreach ($tables as $t){
			$tmpData = $this->HavycChart_model->getTableData($t->id);
			$datasetTables[] = $tmpData;
		}
		
		//print_r($datasetTables); exit;
		
		$data['datasetCharts'] = $dataSetCharts;
		$data['datasetTables'] = $datasetTables;
		
		
		//print_r($dataSetCharts); exit;
		//print_r(json_encode($chartData, JSON_NUMERIC_CHECK));exit;
	
		$this->load->view('datasets/dataset',$data);
	}
	
	
}
