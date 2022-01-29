<?php
class Datacatalog_model extends CI_Model {

  public function __construct()
   {
      $this->load->database();
   }
   
   function createReport($data){
   	$this->db->insert('acs_report', $data);
   }
  
   
   function getDataCatalog(){
   	$this->db->select('*');
   	$this->db->from('data_catalog_topic');
   	$query = $this->db->get();
   	$results = $query->result();
   	//print_r($results); exit;
   	$catalog = array();
   	foreach($results as $r){
   	    
   	    if($r->parent_topic == null){
   	    $tmpData = array();
   	    //add the topic as the 'question'... this is legacy terminology from when a topic was policy question, in a galax far, far away....
   	    $tmpData['question'] = $r;
   	    $elements = array();
   	    //get the data catalog elements, which are just the data indicators/variables associated with this topic
   	    $elements = $this->getDataCatalogElements($r->id);
   	    $tmpData['elements'] = $elements;
   	    $subcategories = array();
   	    $subcategories = $this->getDataCatalogSubTopics($r->id);
   	    $tmpData['subcategories'] = $subcategories;
   	    $tmpData['elementCount'] = count($elements);;
   	    
   	    $catalog[] = $tmpData;
   	    } 
   	    
   	    
   	}
   	return $catalog;
   }
   
   function getDataCatalogElements($topicId){

       $this->db->select('variable_name, field_name, status, data_meta, data_catalog_data.id');
       $this->db->from('data_catalog_topic_data_map');
       $this->db->join('data_catalog_data','data_catalog_data.id = data_catalog_topic_data_map.data');
       $this->db->where('topic', $topicId);
       $query = $this->db->get();
       $results = $query->result();
       return $results;
   }
   
  
   function getDataCatalogSubTopics($topicId){
       //first get the topics...
       $this->db->select('*');
       $this->db->from('data_catalog_topic');
       $this->db->where('parent_topic', $topicId);
       $query = $this->db->get();
       $results = $query->result();
       //print_r($results);
       $catalog = array();
       foreach($results as $r){
           //print_r($r);
           $tmpData = array();
           //add the topic as the 'question'... this is legacy terminology from when a topic was policy question, in a galax far, far away....
           $tmpData['question'] = $r;
           $elements = array();
           //get the data catalog elements, which are just the data indicators/variables associated with this topic
           $elements = $this->getDataCatalogElements($r->id);
           $tmpData['elements'] = $elements;
           $subcategories = array();
           $subcategories = $this->getDataCatalogSubTopics($r->id);
           $tmpData['subcategories'] = $subcategories;
           $tmpData['elementCount'] = count($elements);
           
           $catalog[] = $tmpData;
       }
       return $catalog;
       //print_r($catalog); exit;
       //print_r($results); exit;
       
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