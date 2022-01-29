<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require getcwd().DIRECTORY_SEPARATOR.'application/libraries/api'.DIRECTORY_SEPARATOR.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class v1 extends RestController {
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{

	}
	
	public function acs_data_post(){
		
		$variables = $_POST['variables'];
		$dataset = $_POST['dataset'];
		$year = $_POST['year'];
		$geography = $_POST['geography'];
		$state = $_POST['state'];
	
		$this->load->model('Census_model');
		$data = $this->Census_model->acsData($state,$variables,$dataset,$year,$geography);
		
		
		//print_r($data);exit;
		
	
		if ( isset($data) )
		{
			$this->response( $data, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No data available'
					], 404 );
		}
	
	}
	
	public function acs_data_combine_post(){
	
		$variables = $_POST['variables'];
		$dataset = $_POST['dataset'];
		$year = $_POST['year'];
		$geography = $_POST['geography'];
		$variableName = $_POST['variableName'];
		$state = '50';
		
		$this->load->model('Census_model');
		$data = $this->Census_model->combineAcsData($state,$variables,$dataset,$year,$geography,$variableName);
	
		//print_r($data); 
		//print_r($results);
		//exit;

		if ( isset($data) )
		{
			$this->response( $data, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No data available'
					], 404 );
		}
	
	}
	
	public function acs_data_group_post(){
	
		$data = array();
		$variables = $_POST['variables'];
		$dataset = $_POST['dataset'];
		$year = $_POST['year'];
		$geography = $_POST['geography'];
		$variableName = $_POST['variableName'];
		$state = '50';
		$geoGroup = $_POST['geoGroup'];
		$dataCombined = $_POST['dataCombined'];
		
		$this->load->model('Census_model');
		if($dataCombined == '1'){
			$data = $this->Census_model->combineAcsData($state,$variables,$dataset,$year,$geography,$variableName);
		} else {
			$data = $this->Census_model->acsData($state,$variables,$dataset,$year,$geography);
		}
		
		$gdata = $this->Census_model->groupAcsData($data,$geoGroup);
		//print_r($gdata); exit;
	
		if ( isset($gdata) )
		{
			$this->response( $gdata, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No data available'
					], 404 );
		}
	
	}
	
	public function acs_dataset_post(){
	
		/*
		$variables = $_POST['variables'];
		$dataset = $_POST['dataset'];
		$year = $_POST['year'];
		$geography = $_POST['geography'];
		$variableName = $_POST['variableName'];
		$state = $_POST['state'];
		*/
	
		$this->load->model('Census_model');
		$data = $this->Census_model->saveAcsDataset($_POST);
	
		//print_r($data);
		//print_r($results);
		//exit;
	
		if ( isset($data) )
		{
			$this->response( $data, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No data available'
					], 404 );
		}
	
	}

	public function census_chart_get($id = null){
	
		$this->load->model('Census_model');
		$results = $this->Census_model->getReportData($id);

	
		if ( isset($results) )
		{
			$this->response( $results, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No data available'
					], 404 );
		}
	
	}
	
	public function combine_processed_acs_post(){
	    
	    $acsData = $_POST['acsdata'];
	    $variables = $_POST['variables'];
	    $variableName = $_POST['variableName'];
	    
	    $this->load->model('Census_model');
	    $data = $this->Census_model->combineProcessedAcsData($acsData,$variables, $variableName);
	    
	    //print_r($data);
	    //print_r($results);
	    //exit;
	    
	    if ( isset($data) )
	    {
	        $this->response( $data, 200 );
	    }
	    else
	    {
	        $this->response( [
	            'status' => false,
	            'message' => 'No data available'
	        ], 404 );
	    }
	    
	}
	
	public function fetch_acs_post(){
	    
	    $variables = $_POST['variables'];
	    $year = $_POST['year'];
	    $geography = $_POST['geography'];
	    $state = $_POST['state'];
	    $dataset = $_POST['dataset'];
	    
	    $this->load->model('Census_model');
	    $data = $this->Census_model->fetchAcsData($variables,$dataset,$year,$geography,$state);
	    
	    
	    //print_r($data);exit;
	    
	    
	    if ( isset($data) )
	    {
	        $this->response( $data, 200 );
	    }
	    else
	    {
	        $this->response( [
	            'status' => false,
	            'message' => 'No data available'
	        ], 404 );
	    }
	    
	}

    public function fips_get($geoType, $geo){
        //get a fips code for a given geography type and name

        $this->load->model('Census_model');
        $data = $this->Census_model->getFipsFromGeography($geoType, $geo);

        //print_r($data);exit;

        if ( isset($data) )
        {
            $this->response( $data, 200 );
        }
        else
        {
            $this->response( [
                'status' => false,
                'message' => 'No data available'
            ], 404 );
        }

    }
	
	public function group_processed_acs_post(){
	    
	    $acsData = $_POST['acsdata'];
	    $geoGroup = $_POST['geogroup'];
	    
	    $this->load->model('Census_model');
	    $data = $this->Census_model->groupProcessedAcsData($acsData,$geoGroup);
	    
	    //print_r($data);
	    //print_r($results);
	    //exit;
	    
	    if ( isset($data) )
	    {
	        $this->response( $data, 200 );
	    }
	    else
	    {
	        $this->response( [
	            'status' => false,
	            'message' => 'No data available'
	        ], 404 );
	    }
	    
	}
	
	public function process_acs_post(){
	    
	    //this takes a raw json response from the Cenus API, and converts it into a readable array of values
	    
	    $acsData = $_POST['acsdata'];
	    
	    $this->load->model('Census_model');
	    $data = $this->Census_model->processRawAcsData($acsData);
	    
	    
	    //print_r($data);exit;
	    
	    
	    if ( isset($data) )
	    {
	        $this->response( $data, 200 );
	    }
	    else
	    {
	        $this->response( [
	            'status' => false,
	            'message' => 'No data available'
	        ], 404 );
	    }
	    
	}

		public function census_table_get($id = null){
	
		$this->load->model('Census_model');
		$results = $this->Census_model->getReportData($id);

	
		if ( isset($results) )
		{
			$this->response( $results, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No data available'
					], 404 );
		}
	
	}
	
	public function havyc_areas_of_focus_get(){
	
		$this->load->model('Havyc_model');
		$results = $this->Havyc_model->getAreasOfFocus();
	
		if ( isset($results) )
		{
			$this->response( $results, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No areas of focus found'
					], 404 );
		}
	
	}
	
	public function havyc_chart_get($id = null){
	
		$this->load->model('HavycChart_model');
		$chart = $this->HavycChart_model->getChart($id);
		//print_r($chart);exit;
		if($chart->chart_type == 'line'){
			$results = $this->HavycChart_model->getLineChart($chart->id);
		}
		if($chart->chart_type == 'horizontal bar' || $chart->chart_type == 'bar'){
			$results = $this->HavycChart_model->getBarChart($chart->id);
		}
		if($chart->chart_type == 'stacked bar' ){
			$results = $this->HavycChart_model->getStackedBarChart($chart->id);
		}
		if($chart->chart_type == 'pie'){
			$results = $this->HavycChart_model->getPieChart($chart->id);
		}
		
		if($chart->chart_type == 'doughnut'){
			$results = $this->HavycChart_model->getPieChart($chart->id);
		}
	
		if ( isset($results) )
		{
			$this->response( $results, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No data available'
					], 404 );
		}
	
	}
	
	public function havyc_dataset_data_get($datasetId= null){
	
		$this->load->model('Havyc_model');
		$results = $this->Havyc_model->getDataForDataset($datasetId);
	
		if ( isset($results) )
		{
			$this->response( $results, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No data available'
					], 404 );
		}
	
	}
	
	public function havyc_dataset_get($id = null){
	
		$this->load->model('Havyc_model');
		$results = $this->Havyc_model->getDataset($id);
		
		//print_r($results); exit;
	
		if ( isset($results) )
		{
			$this->response( $results, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No data available'
					], 404 );
		}
	
	}
	
	public function havyc_table_get($id = null){
	
		$this->load->model('HavycChart_model');
		$table = $this->HavycChart_model->getTable($id);
		$results = $this->HavycChart_model->getTableData($table->id);
		
	
		if ( isset($results) )
		{
			$this->response( $results, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No data available'
					], 404 );
		}
	
	}

    public function medianincome_get($geo= null, $geoVal = null ){
    //get the median income for an area.  currently this is only supports the 'zip' geo
        $this->load->model('Census_model');
        $results = array();
        if($geo != null && $geoVal != null) {
            $results = $this->Census_model->getMedianIncome($geo, $geoVal);
        }

        if ( isset($results) )
        {
            $this->response( $results, 200 );
        }
        else
        {
            $this->response( [
                'status' => false,
                'message' => 'No data available'
            ], 404 );
        }

    }

	public function nsch_adverse_childhood_get($years = '2016~2017~2018~2019',$ages = 'havyc', $states='50'){

		$this->load->model('NSCH_model');
		$retVal = array();
		if($ages == 'havyc'){ 
			$ages = '0~1~2~3~4~5~6~7~8';
		}
		$aceGroup = array (
			'0' => 'No adverse childhood experiences',
			'1' => 'One adverse childhood experience',
			'2' => 'Two or more adverse childhood experiences'
		);
		//print_r($ages); exit;
		$results = $this->NSCH_model->getAdverseChildhoodExperiences($years, $ages, $states);
		//exit;
		$totalWeightedFrequency = 0;
		foreach($results as $r){
			$totalWeightedFrequency += $r->FWCANNUAL;
		}
		foreach($results as $r){
			$r->Percent = ($r->FWCANNUAL / $totalWeightedFrequency) * 100;
			$r->AceGroup = $aceGroup[$r->ACE2more_1618];
			$retVal[] = $r;
		}

		if ( isset($retVal) )
		{
			$this->response( $retVal, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No areas of focus found'
					], 404 );
		}
	}

	public function nsch_any_bem_get($years = '2016~2017~2018',$ages = 'havyc'){
	
		$this->load->model('NSCH_model');
		//if there are no parameters passed in, group by defaults as per BBF and HAVYC
		if($ages == 'havyc'){
			//group by under 3, 3-5, 6-8 years
			$ageGroups = array(
			'Under 3 Years' => '0~1~2',
			'3 to 5 Years' => '3~4~5',
			'6 to 8 Years' => '6~7~8');
			$groupedResutls = array();
			foreach($ageGroups as $label=>$a){
				$results = $this->NSCH_model->getAnyBEM($years, $a);
				$weightedTotal = 0;
				foreach($results as $r){
					$weightedTotal += $r["WeightedCount"];
				}
				foreach($results as $r){
					$pct = ($r["WeightedCount"] / $weightedTotal) * 100;
					$r["Percent"] = $pct;
					$r["AgeGroup"] = $label;
					//by default for HAVYC only count the yes values
					if($r["HasCondition"] == 'Yes'){
						$retVal[] = $r;
					}
					
				}

			}
			//print_r($retVal); exit;

		} else {
			$results = $this->NSCH_model->getAnyBEM($years, $ages);
			$retVal = $results;
		}
		
	
		if ( isset($retVal) )
		{
			$this->response( $retVal, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No areas of focus found'
					], 404 );
		}
	
	}

	public function nsch_adequate_insurance_get($years = '2016~2017~2018~2019',$ages = 'havyc', $states='50'){
	
	/*Data for whether children under 9 have adequate health insurance at the time of survey */
		$this->load->model('NSCH_model');
		
		if($ages == 'havyc'){ 
			$ages = '0~1~2~3~4~5~6~7~8~9~10~11~12~13~14~15~16~17~18';
		}
		//array of states for labels
		//TODO: if we want to compare multiple states to each other, we need to add the states to the results loop below, and
		//set the state label there
		$FIPPSTArray = $this->getFippstArray();
		//create state label
		$statesLabel = $FIPPSTArray[$states];
		$retVal = array();
		$labels = array(
			1 => "Currently insured, consistently insured and insurance is adequate",
			2 => "Not currently insured, had coverage gap or inadequate insurance",
			3 => "Total"
		);

		//for each year find the values for the states passed in and also for the US overall
		$reportYears = explode('~',$years);
		//print_r($reportYears); exit;
		foreach($reportYears as $y){
			$yearArray = array($y);
			$results = $this->NSCH_model->getAdequateInsurance($y, $ages,$states);
			$resultsUS = $this->NSCH_model->getAdequateInsurance($y, $ages,'US');
			
			$totalFrequency = 0;
			$totalWeightedFrequency = 0;
			foreach($results as $r){
				$totalWeightedFrequency += $r->WeightedFrequency;						 
			}
			foreach($results as $r){
				$r->Percent = ($r->WeightedFrequency / $totalWeightedFrequency) * 100;
				$r->Label = $labels[$r->Insurance_1618];
				$r->FIPPST = $statesLabel;
				$r->Year = $y;
				$retVal[] = $r;
			}

			$totalFrequencyUS = 0;
			$totalWeightedFrequencyUS = 0;
			foreach($resultsUS as $rUS){
				$totalWeightedFrequencyUS += $rUS->WeightedFrequency;						 
			}
			foreach($resultsUS as $rUS){
				$rUS->Percent = ($rUS->WeightedFrequency / $totalWeightedFrequencyUS) * 100;
				$rUS->Label = $labels[$rUS->Insurance_1618];
				$rUS->FIPPST = "US";
				$rUS->Year = $y;
				//$retVal[] = $rUS;
			}
		}

		//print_r($retVal); exit;
	
		if ( isset($retVal) )
		{
			$this->response( $retVal, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No adequate insurance data found'
					], 404 );
		}
	
	}	

	public function nsch_dental_get($years = '2016~2017~2018',$ages = 'havyc'){
		
		$this->load->model('NSCH_model');
		
		//print_r($results); exit;
		//if there are no parameters passed in, group by defaults as per BBF and HAVYC
		if($ages == 'havyc'){
			//group by under 3, 3-5, 6-8 years
			$ageGroups = array(
			'Under 3 Years' => '1~2',
			'3 to 5 Years' => '3~4~5',
			'6 to 8 Years' => '6~7~8');
			$groupedResutls = array();
			foreach($ageGroups as $label=>$a){
				$results = $this->NSCH_model->getPreventiveDental($years, $a);
				$weightedTotal = 0;
				foreach($results as $r){
					$weightedTotal += $r["WeightedFrequency"];
				}
				foreach($results as $r){
					$pct = ($r["WeightedFrequency"] / $weightedTotal) * 100;
					$r["Percent"] = $pct;
					$r["AgeGroup"] = $label;
					if($r["PrevDent_1618"] == 1){
					$r["DentalVisit"] = 'Had one or more preventive dental care visit';
					} else {
					$r["DentalVisit"] = 'Did not have one or more preventive dental care visit';
					}
					$retVal[] = $r;
					
					
				}

			}
			//print_r($retVal); exit;

		} else {
			$results = $this->NSCH_model->getPreventiveDental($years, $ages);
			$retVal = $results;
		}
		
		if ( isset($retVal) )
		{
			$this->response( $retVal, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No areas of focus found'
					], 404 );
		}
	}

	public function nsch_flourishing_get($years = '2016~2017~2018',$ageGroup = '5m6y'){
	
		$this->load->model('NSCH_model');
		if($ageGroup == '5m6y'){
			$results = $this->NSCH_model->getFlourishing5m6y($years);
		} else {
			$results = $this->NSCH_model->getFlourishing6y8y($years);
		}
		
		//print_r($results); exit;
		//create percentages from the weighted totals
		$weightedTotal = 0;
		foreach($results as $r){
			$weightedTotal += $r["WeightedCount"];
		}
		foreach($results as $r){
			$pct = ($r["WeightedCount"] / $weightedTotal) * 100;
			$r["Percent"] = $pct;
			$retVal[] = $r;
		}
	
		if ( isset($retVal) )
		{
			$this->response( $retVal, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'No areas of focus found'
					], 404 );
		}
	
	}

    public function nsch_mental_health_get($years = '2016~2017~2018',$ages = 'havyc'){

        $this->load->model('NSCH_model');

        //print_r($results); exit;
            $results = $this->NSCH_model->getMentalHealth($years, $ages);
            $retVal = $results;

        if ( isset($retVal) )
        {
            $this->response( $retVal, 200 );
        }
        else
        {
            $this->response( [
                'status' => false,
                'message' => 'No mental health found'
            ], 404 );
        }
    }

	public function report_get(){
		
		$results = array('TEST'=>'Test Value');

		
		if ( isset($results) )
		{
			$this->response( $results, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'A valid content ID must be provided'
					], 404 );
		}
		
	}
	
	public function search_concepts_get($searchTxt){
	
		$results = array();
		if($searchTxt != null && $searchTxt != ''){
			$this->load->model('Census_model');
			$results = $this->Census_model->searchConcepts($searchTxt);
		}
	
		if ( isset($results) )
		{
			$this->response( $results, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'A valid content ID must be provided'
					], 404 );
		}
	
	}
	
	public function search_concept_variables_get($concept){
	
		$results = array();
		if($concept != null && $concept != ''){
			$this->load->model('Census_model');
			$results = $this->Census_model->searchConceptVariables(urldecode($concept));
		}
	
		if ( isset($results) )
		{
			$this->response( $results, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'A valid content ID must be provided'
					], 404 );
		}
	
	}
	
	public function getFippstArray(){
		return array('50'=>'Vermont','33'=>'New Hampshire'
		);
	}
	
	public function datacatalog_get(){
	  
	    $this->load->model('Datacatalog_model');
	    $results =  $this->Datacatalog_model->getDataCatalog();
    	if ( isset($results) )
    	{
    	    $this->response( $results, 200 );
    	}
    	else
    	{
    	    $this->response( [
    	        'status' => false,
    	        'message' => 'A valid content ID must be provided'
    	    ], 404 );
    	}
	
	}
}
