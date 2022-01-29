<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Havyc extends MY_Controller {

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
		
		$this->load->view('havyc/index',$data);
	}
	
	public function add_data(){
		
		//print_r($_POST); 
		
		$datasetId = $_POST['dataset_id'];
		$this->load->model('Havyc_model');
		
		$dataset = $this->Havyc_model->getDataset($datasetId);
		$data = array();
		foreach($_POST as $key => $val){
			if($key != 'dataset_id'){
				$data[$key] = $val;
			}
		}
		
		$tableName = $dataset->data_table;
		$this->Havyc_model->addData($tableName, $data);
		
		$this->load->helper("Url");
		redirect('/havyc/dataset/'.$datasetId);
		//print_r($data);
		//exit;
		
	}
	
	public function chart_embed($chartId){
		
		$data = array();
		
		$this->load->model('Havyc_model');
		$this->load->model('HavycChart_model');
		$c= $this->HavycChart_model->getChart($chartId);
		if($c->chart_type == 'line'){
			$chart = $this->HavycChart_model->getLineChart($c->id);
		}
		if($c->chart_type == 'horizontal bar'){
			$chart = $this->HavycChart_model->getBarChart($c->id);
		}
		$data['chart'] = $chart;
		$this->load->view('havyc/embed/chart',$data);
	}
	
	public function charts()
	{
		$data = array();
		$this->load->model('Havyc_model');
	
		$charts = $this->Havyc_model->getCharts();
		$data['charts'] = $charts;
	
		//print_r($dataset); exit;
	
		$this->load->view('havyc/charts',$data);
	}
	
	public function delete_data($datasetId, $dataId){
	
		if( $this->require_role('admin') )
		{
		$this->load->model('Havyc_model');
		$dataset = $this->Havyc_model->getDataset($datasetId);
		$tableName = $dataset->data_table;
		$this->Havyc_model->deleteData($tableName, $dataId);
	
		$this->load->helper("Url");
		redirect('/havyc/dataset/'.$datasetId);
		}
		//print_r($data);
		//exit;
	
	}
	
	public function dataset($id = null)
	{
		if( $this->require_role('admin') )
		{
		
		$data = array();
		$this->load->model('Havyc_model');
		$this->load->model('HavycChart_model');
	
		if($id != 'create'){
			$dataset = $this->Havyc_model->getDataset($id);
			$data['dataset'] = $dataset;

			$datasetdata = $this->Havyc_model->getDatasetData($id);
			$data['datasetdata'] = $datasetdata;
		
			$columns = explode(',', $dataset->columns);
			$data['columns'] = $columns;
		}
		
		
		
		
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
		
		//$charts = $this->HavycChart_model->getChartsForDataset($id);
		$dataSetCharts = array();
		$datasetTables = array();
		//print_r($charts); exit;
		
		//print_r($dataSetCharts); exit;
		//print_r(json_encode($chartData, JSON_NUMERIC_CHECK));exit;
	
		$this->load->view('havyc/dataset',$data);

		}
		
	}
	
	public function datasets($id = null)
	{
		$data = array();
		$this->load->model('Havyc_model');
		
		$areasOfFocus = $this->Havyc_model->getAreasOfFocus();
		$data['areasOfFocus'] = $areasOfFocus;
		
		$datasets= $this->Havyc_model->getDatasets(null);
		$data['dataset'] = $datasets;
		
		//print_r($datasets); exit;
	
		$this->load->view('havyc/datasets',$data);
	}

	function save_dataset(){
		//print_r($_POST);exit;

		if(isset($_POST["id"])){
			//echo 'updating'; exit;
			$id = $_POST["id"];
			$this->load->model('Havyc_model');
			$data = array();
			$data["title"] = $_POST["title"];
			$data["data_table"] = $_POST["data_table"];
			$data["owner"] = $_POST["owner"];
			$data["columns"] = $_POST["columns"];
				//print_r($data); exit;
			$this->Havyc_model->updateDataset($_POST["id"], $data);
		} else {
			$id = $this->Havyc_model->createDataset();
		}

		$this->load->helper("Url");
		redirect('/havyc/dataset/'.$id);
	}
	
}
