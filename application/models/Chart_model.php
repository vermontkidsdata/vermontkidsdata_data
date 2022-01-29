<?php
class Chart_model extends CI_Model {

  public function __construct()
   {
      $this->load->database();
   }
   
   function createChart($data){
   	$this->db->insert('acs_report', $data);
    return $this->db->insert_id();
   }
   
   
   function getChart($id){
   	
   	$this->db->select('*');
   	$this->db->from('google_chart');
   	$this->db->where('id',$id);
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results[0];
   	
   }
   
   function getChartRows($chartId){
   
   	$this->db->select('*');
   	$this->db->from('google_line_chart_row');
   	$this->db->where('chart_id',$chartId);
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   
   }
     
   
}