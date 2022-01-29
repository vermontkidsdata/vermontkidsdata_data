<?php
class Havyc_model extends CI_Model {

  public function __construct()
   {
      $this->load->database();
   }
   
   function addData($tableName, $data){
   	$this->db->insert($tableName, $data);
   }
   
   function deleteData($tableName, $dataId){
   	$this->db->delete($tableName, array( 'id' => $dataId));
   }
   
   function getAgeGroups(){
   	$this->db->select('*');
   	$this->db->from('havyc_age_group');
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   }
   
   function getAreasOfFocus(){
   	$this->db->select('*');
   	$this->db->from('havyc_areas_of_focus');
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   }
   
   function getChartDatasets(){
   	$this->db->select('year, population');
   	$this->db->from('havyc_child_population_by_age');
   	$this->db->where('age_group',2);
   	$query = $this->db->get();
   	$results = $query->result();
   	$data = array();
   	foreach($results as $r){
   		$tmpArray = array();
   		$tmpArray['x'] = $r->year;
   		$tmpArray['y'] = $r->population;
   		$data[] = $tmpArray;
   	}
   	return $data;
   }
   
   function getCharts(){
   
   	$this->db->select('*');
   	$this->db->from('havyc_chart');
   	$this->db->order_by('chart_title');
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   }
   
   function getChartsForDataset($datasetId){
   	$this->db->select('*');
   	$this->db->from('havyc_chart');
   	$this->db->where('dataset',$datasetId);
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   }
   
   function getColumnNames($tableName){
   	$this->db->select('`COLUMN_NAME`');
   	$this->db->from('`INFORMATION_SCHEMA`.`COLUMNS`');
   	$this->db->where("`TABLE_SCHEMA`='havyc'");
   	$this->db->where("`TABLE_NAME`",$tableName);
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   }
   
   function getDataForDataset($datasetId){
   	
   	$dataset = $this->getDataset($datasetId);
   
   	$this->db->select('*');
   	$this->db->from($dataset->data_table);
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   	
   	
   }
   
   function getDataset($id = null){
   	$retVal = array();
   	$this->db->select('*');
   	$this->db->from('havyc_dataset');
   	if($id != null){
   		$this->db->where('id', $id);
   	}
   	$query = $this->db->get();
   	$results = $query->result();
   	if(isset($results[0])){ 
   		$retVal = $results[0]; 
   	}
   	return $retVal;
   }
   
   function getDatasets(){

   	$this->db->select('*');
   	$this->db->from('havyc_dataset');
    $this->db->order_by('title');
   	$query = $this->db->get();
   	$results = $query->result();
   	$retVal = array();
   	foreach($results as $r){
   		$id = $r->id;
   		$charts = $this->getChartsForDataset($id);
   		$r->charts = $charts;
   		$retVal[] = $r;
   	}
   	return $retVal;
   }
   
   function getDatasetColumnValues($datasetId, $columnName){
   	 $retVal = array();
   	 
   	 $dataset = $this->getDataset($datasetId);
   	 $table = $dataset->data_table;
   	 $this->db->select($columnName);
   	 $this->db->from($table);
   	 $this->db->distinct();
   	 $query = $this->db->get();
   	 $results = $query->result();
   	 $retVal = $results;
   	//echo $this->db->last_query();

   	 return $retVal;
   }
   
   function getDatasetData($datasetId){
   	
   	$dataset = $this->getDataset($datasetId);   	
   	$retVal = array();
   	$this->db->select('*');
   	$this->db->from($dataset->data_table);
   	$this->db->where('is_current', '1');
   	$this->db->order_by('id desc');
   	$query = $this->db->get();
   	$results = $query->result();   	
   	return $results;
   	
   }
   
   function getExternalDatasetData($datasetId){
   
   	$dataset = $this->getDataset($datasetId);
   	$retVal = array();
   	$this->db->select('*');
   	$this->db->from($dataset->data_table);
   	$this->db->order_by('id desc');
   	$this->db->limit(100);
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   
   }
   
   function getGeographies(){
   	$this->db->select('*');
   	$this->db->from('havyc_geography');
   	$this->db->order_by('name');
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   }
   
   function getHouseholdTypes(){
   	$this->db->select('*');
   	$this->db->from('havyc_household_type');
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   }
   
   function getRace(){
   	$this->db->select('*');
   	$this->db->from('havyc_race');
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   }
   
   function getSchoolYears(){
   	$this->db->select('*');
   	$this->db->from('havyc_school_year');
   	$this->db->order_by('id');
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   }
   
   function getYears(){
   	$this->db->select('*');
   	$this->db->from('havyc_years');
   	$this->db->where('year > 1998');
   	$this->db->order_by('year');
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   }

   function updateDataset($id, $data){
        $this->db->update('havyc_dataset', $data, array("id" => $id));
    }

    function createDataset($data){
        $this->db->insert('havyc_dataset', $data);
   	    return $this->db->insert_id();
    }
   
}