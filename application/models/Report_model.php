<?php
class Report_model extends CI_Model {

  public function __construct()
   {
      $this->load->database();
   }
   
   function createReport($data){
   	$this->db->insert('acs_report', $data);
   }
   
   //TODO: need to figure out how to pull dimensions associated with variable groups 
   //from the various tables and/or from the admin UI inputs
   
   function getDimensionsForReport($reportId = null){
   	$this->db->select('dimensions');
   	$this->db->from('acs_report');
   	$this->db->where('id_acs_report',$reportId);
   	$query = $this->db->get();
   	$results = $query->result();
   	//print_r($results); exit;
   	$dimensions = $results[0]->dimensions;
   	$dimArray = explode('~', $dimensions);
   	$names = array();
   	foreach($dimArray as $d){
   		$names[] = '!!'.$d;
   	}
   	return $names;
   }
   
   function getDimensionNames($variableGroup = null) {
   	$names = array();
   	if($variableGroup == 'B17001H'){
   		$names[] = '!!White Alone, Not Hispanic or Latino';
   	}
   	if($variableGroup == 'B17001I'){
   		$names[] = '!!Hispanic or Latino';
   	}
   	if($variableGroup == 'B17024'){
   		$names[] = '!!Under 50%';
   		$names[] = '!!Under 75%';
   		$names[] = '!!Under 100%';
   		$names[] = '!!Under 125%';
   		$names[] = '!!Under 150%';
   		$names[] = '!!Under 175%';
   		$names[] = '!!Under 185%';
   		$names[] = '!!Under 200%';
   		$names[] = '!!Under 300%';
   		$names[] = '!!Under 400%';
   		$names[] = '!!Under 500%';
   		$names[] = '!!500% and Over';
   	}
   	if($variableGroup == 'B19125'){
   		$names[] = '!!Median Income';
   	}
   	if($variableGroup == 'B09010'){
   		$names[] = '!!All';
   		$names[] = '!!In family households';
   		$names[] = '!!In married-couple family';
   		$names[] = '!!In male householder, no wife present, family';
   		$names[] = '!!In female householder, no husband present, family	';
   		$names[] = '!!In nonfamily households';
   	}
   	if($variableGroup == 'S0901'){
   		$names[] = '!!Children under 18 years in households';
   		$names[] = '!!Race and Hispanic or Latino Origin, One race';
   		$names[] = '!!Race and Hispanic or Latino Origin, White';
   		$names[] = '!!Race and Hispanic or Latino Origin, Black or African American';
   		$names[] = '!!Race and Hispanic or Latino Origin, American Indian and Alaska Native';
   		$names[] = '!!Race and Hispanic or Latino Origin, Asian';
   		$names[] = '!!Race and Hispanic or Latino Origin, Native Hawaiian and Other Pacific Islander';
   		$names[] = '!!Race and Hispanic or Latino Origin, Some other race';
   		$names[] = '!!Race and Hispanic or Latino Origin, Two or more races';
   	}
   	return $names;
   
   }
   
   function getReport($reportId){
   	
   	$this->db->select('*');
   	$this->db->from('acs_report');
   	$this->db->where('id_acs_report',$reportId);
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results[0];
   	
   }
   
   function getReports($reportIds = array()){
   
   	$this->db->select('*');
   	$this->db->from('acs_report');
   	if(count($reportIds) > 0) {
   		$this->db->where_in('id_acs_report',$reportIds);
   	}
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   
   }
   
   function getReportTemplates(){
   	$templates = array();
   	$templates[] = 'Estimate/Year/Sex/Age';
   	$templates[] = 'Estimate/Year/Sex/Age/Dimension';
   	return $templates;
   }
   
   function getReportYears(){
   	$this->db->select('year');
   	$this->db->distinct();
   	$this->db->from('acs_us_data');
   	$this->db->order_by('year');
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   }
     
   
}