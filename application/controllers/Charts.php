<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Charts extends MY_Controller {

	/**
	 * Main Controller for managing data and assembling the How are Vermont's Young Children report
	 */
	public function index()
	{
		
	}
	
	function add_dataset(){

		$params = array();
		$label = $_POST['label'];
		foreach($_POST as $key =>$val){
			if($key == 'chart_id' || $key == 'background_color' || $key == 'border_color' || $key == 'fill'){
				$params[$key] = $val;
			}
		}
		$params['label'] = $_POST[$label];		
		//print_r($params); exit;
		$this->load->model('HavycChart_model');
		$this->HavycChart_model->addChartDataset($params);
		$this->load->helper("Url");
		redirect('/charts/edit/'.$params['chart_id']);
	}
	
	public function delete_dataset($chartId, $datasetId){
		
		if( $this->require_role('admin') )
		{
		
		$this->load->model('HavycChart_model');
		//print_r($datasetId); exit;
		$this->HavycChart_model->deleteChartDataset($datasetId);
		
		$this->load->helper("Url");
		redirect('/charts/edit/'.$chartId);
		
		}
	}
	
	public function edit($chartId = null){
		
		//print_r($_POST); 
		
		if( $this->require_role('admin') )
		{
		
		$data = array();
		$this->load->model('Havyc_model');
		$this->load->model('HavycChart_model');
		$datasets= $this->Havyc_model->getDatasets(null);
		$data['datasets'] = $datasets;
		
		$this->load->model('Census_model');
		$censusReports = $this->Census_model->getReports();
		$data['censusReports'] = $censusReports;
			
		$charts = $this->Havyc_model->getCharts();
		$data['charts'] = $charts;
		
		$colors = $this->HavycChart_model->getDatasetColors();
		$data['colors'] = $colors;
		
		if($chartId != null){

			$chart = $this->HavycChart_model->getChart($chartId);
			$data['chart'] = $chart;
			$chartDatasets = $this->HavycChart_model->getChartDatasets($chartId);
			//print_r($chartDatasets); exit;
			$data['chartDatasets'] = $chartDatasets;

			//get unique column values for adding datasets
			$chartDataset = $this->Havyc_model->getDataset($chart->dataset);
			//print_r($chartDataset); exit;

			if(isset($chartDataset->columns)){
				//if there is a dataset, get the columsn from that
				$datasetColumns = $chartDataset->columns;
				//print_r($datasetColumns);
				//print_r($chart->dataset);
				$columns = explode(',',$datasetColumns);
				//print_r($columns); exit;
				$chartDatasetColumns = array();
				//exit;
				foreach($columns as $c){
					//echo '<br>'.$c.'<br>';
					if($c != 'is_current' && $c != 'id'){
					
						$values = array();					
						$values = $this->Havyc_model->getDatasetColumnValues($chart->dataset, $c);					
						$chartDatasetColumns[$c] = $values;
					
					}

					$data['chartDatasetColumns'] = $chartDatasetColumns;		
				
				}

			}



			//print_r($data); exit;

			//if(isset($chartDatasetColumns)){
			
			$chartData = array();

				if($chart->chart_type == 'line'){
					$tmpData = $this->HavycChart_model->getLineChart($chart->id);
					$chartData = $tmpData;
				}
				
				if($chart->chart_type == 'pie' || $chart->chart_type == 'doughnut' ){
					$tmpData = $this->HavycChart_model->getPieChart($chart->id);
					$chartData = $tmpData;
				}
				
				if($chart->chart_type == 'horizontal bar' || $chart->chart_type == 'bar' ){
					$tmpData = $this->HavycChart_model->getBarChart($chart->id);
					$chartData = $tmpData;
				}

				if($chart->chart_type == 'stacked bar' ){
					$tmpData = $this->HavycChart_model->getStackedBarChart($chart->id);
					$chartData = $tmpData;
				}
			
				//print_r($chartData);	exit;

				$data['chartData'] = $chartData;

			//}
		}
		
		$this->load->view('admin/chart/edit',$data);
		//exit;
		
		}
		
	}
	
	public function save($chartId = null){
		
	    if( $this->require_role('admin') )
	    {
    		$params = $_POST;
    		//convert the census reports to a dataset values
    		if($params['chart_datasource'] == 'census'){
    		    $params['dataset'] = $_POST['census_report'];
    		    $params['dataset_denominator'] = $_POST['census_report_denominator'];       		    
    		}
    		//remove the census specific values
    		unset($params['census_report']);
    		unset($params['census_report_denominator']);
    		//print_r($params); exit;
    
    		$this->load->model('HavycChart_model');
    		if($chartId == null){
    			$chartId = $this->HavycChart_model->createChart($params);
    		} else {
    			$this->HavycChart_model->updateChart($chartId,$params);
    		}
    		
    		$this->load->helper("Url");
    		redirect('/charts/edit/'.$chartId);
		
	    }

		
	}
	
	
	
}
