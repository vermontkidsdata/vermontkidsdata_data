<?php
class Reports_model extends CI_Model {

  public function __construct()
   {
      $this->load->database();
   }
   
   function createReport($data){
   	$this->db->insert('acs_report', $data);
   }
   
   
   
   function getReports($reportIds = array()){
   
   	//get a list of the various reports that users could save
   	$this->db->select('census_datasets.name as datasetname,acs_dataset.id,acs_dataset.name, acs_dataset.year,acs_dataset.geography,acs_dataset.geo_group, gaz_states.NAME as STATENAME');
   	$this->db->from('acs_dataset');
   	$this->db->join('gaz_states','gaz_states.GEOID = acs_dataset.state');
   	$this->db->join('census_datasets','census_datasets.value = acs_dataset.dataset');
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   
   }
     
   
}