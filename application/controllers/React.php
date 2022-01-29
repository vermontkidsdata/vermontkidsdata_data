<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require getcwd().DIRECTORY_SEPARATOR.'application/libraries/api'.DIRECTORY_SEPARATOR.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class React extends RestController {
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
	    $this->response( 'nothing here', 200 );
	}
	
	public function upload_geography_map_get(){
	    
	    $fileName = 'gaz_head_start.csv';
	    //$this->upload_geography_map($fileName);
	}
	
	public function upload_geography_map($fileName){
	    
	   //upload the current csv into the geography map table
	   $userId = '2147484848';
	   //$fileName = 'gaz_head_start.csv';
	   $uploadDirectory = getcwd().DIRECTORY_SEPARATOR.'uploads';
	  // echo $uploadDirectory.DIRECTORY_SEPARATOR.$fileName; exit;
	   $fileHandle = fopen($uploadDirectory.DIRECTORY_SEPARATOR.$fileName, "r");
	   
	   //print_r($fileHandle); exit;
	   
	   while (($data = fgetcsv($fileHandle, 1000, ",")) !== FALSE)
	   {
	       //put the values into an array we can work with
	       $geo_array[] = $data;
	   }
	   
	   fclose($fileHandle);
	   
	   //print_r($geo_array);
	   
	   //find the 
	   
	   //iterate throught the array and add the map entries to the database
	   $this->load->model('Census_model');
	   $gcnt  = 0;
	   foreach($geo_array as $g){
	       $countySubdivisionName = '';
	       $gData = array();
	       //ignore the first header row
	       if($gcnt > 0 && isset($g[0])){    	       
    	       $gData['gaz_county_subdivision'] = $g[0];
    	       //get the county subdivision name from the gazetteer table
    	       $countySubdivisionName = $this->Census_model->getGeographyCountySubdivision($g[0]);
    	       if($countySubdivisionName != null && $countySubdivisionName != ''){
    	           $gData['gaz_county_subdivision_name'] = $countySubdivisionName->NAME;
    	       }
    	       $gData['geography_map_type'] = $g[1];
    	       $gData['geography_map_label'] = $g[2];
    	       $gData['geography_map_name'] = $g[3];
    	       
    	       if(isset($g[4])){
    	           $gData['geography_map_geoid'] = $g[4];
    	       }
    	       $gData['geography_map_owner'] = $userId;
    	       
	       }
	       
	       //only create a record if there are values
	       if($countySubdivisionName != null && $countySubdivisionName != '' && isset($g[1]) && isset($g[2]) && 
	           $g[1] != null  && $g[1] != ''  && $g[2] != null  && $g[2] != ''  ){
	           //we have enough data... create the record
	           //print_r($gData);
	           $this->Census_model->saveGeographyMap($gData);
	           $goodRecords[] = $g[0];
	       } else {
	           $badRecords[] = $g[0];
	       }
	       $gcnt ++;
	   }
	   $retVal['loaded'] = $goodRecords;
	   $retVal['rejected'] = $badRecords;
	   return $retVal;

	}
	
	public function upload_post(){
	    
	    $config['upload_path']          = './uploads';
	    $config['allowed_types']        = 'gif|jpg|png|pdf|csv';
	    $config['max_size']             = 100;
	    $config['max_width']            = 1024;
	    $config['max_height']           = 768;
	    
	    $this->load->library('upload', $config);
	    $records = array();
	    if (isset($_FILES['file']['name'])) {
	        if (0 < $_FILES['file']['error']) {
	            $data = 'Error during file upload' . $_FILES['file']['error'];
	        } else {
	            if (file_exists('uploads/' . $_FILES['file']['name'])) {
	                $data = 'File already exists : uploads/' . $_FILES['file']['name'];
	            } else {
	                $this->load->library('upload', $config);
	                if (!$this->upload->do_upload('file')) {
	                    $data = $this->upload->display_errors();
	                } else {
	                    $data = 'File successfully uploaded : ' . $_FILES['file']['name'];
	                    //now that the file was uploaded, write it to the database, and then remove it
	                    $records = $this->upload_geography_map($_FILES['file']['name']);
	                }
	            }
	        }
	    } else {
	        $data = 'Please choose a file';
	    }
	    
	    $retVal['message'] = $data;
	    $retVal['records']  = $records;
	
		if ( isset($records) )
		{
			$this->response( $retVal, 200 );
		}
		else
		{
			$this->response( [
					'status' => false,
					'message' => 'Error uploading file'
					], 404 );
		}
	
	}
	
	
	public function files_get($userId = 0)
	{
	    $data = array();
	    $this->load->model('Census_model');
	    $files = array();
	    $maps = $this->Census_model->getGeographyMapsForUser($userId);
	    foreach($maps as $m){
	        $tmpFile['name'] = $m->geography_map_label;
	        $tmpFile['url'] = '#';
	        $files[] = $tmpFile;
	        
	    }
	    if(empty($files) || count($files) < 1){
	        $tmpFile['name'] = 'No geography files currently uploaded';
	        $tmpFile['url'] = '#';
	        $files[] = $tmpFile;
	    }
	    
	    if ( isset($files) )
	    {
	        $this->response( $files, 200 );
	    }
	    else
	    {
	        $this->response( [
	            'status' => false,
	            'message' => 'No data available'
	        ], 404 );
	    }
	}

}
