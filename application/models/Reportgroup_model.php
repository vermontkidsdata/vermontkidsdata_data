<?php
class Reportgroup_model extends CI_Model {

  public function __construct()
   {
      $this->load->database();
   }
   
   function createReportGroup($data){
   	$this->db->insert('acs_report_group', $data);
   }
   
   function getReportGroups($reportId){
   	
   	$this->db->select('*');
   	$this->db->from('acs_report_group');
   	$this->db->where('report_id',$reportId);
   	$query = $this->db->get();
   	return $query->result();
   	
   }
   
   function getReportGroupReportIds(){
   
   	$this->db->select('report_id');
   	$this->db->from('acs_report_group');
   	$this->db->distinct();
   	$query = $this->db->get();
   	return $query->result();
   
   }
   
   function getReportGroupReports(){
   	 
   	$reportIds = $this->getReportGroupReportIds();
   	foreach($reportIds as $r){
   		$ids[] = $r->report_id;
   	}
   	$this->load->model('Report_model');
   	$reports = $this->Report_model->getReports($ids);
   	return $reports;
   	 
   }
   
   function updateReportGroup($id,$data){
   	$this->db->update('acs_report_group', $data, array('id_acs_report_group' => $id));
   }
   
}