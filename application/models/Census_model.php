<?php
class Census_model extends CI_Model {

  public function __construct()
   {
      $this->load->database();
   }
     
   function acsData($state,$variables,$dataset,$year,$geography){
   	$results = $this->getAcsData($variables,$dataset,$year,$geography);
   	
   	//print_r($results); exit;
   	
   	//put the results into a readable array
   	$variablesArray = explode(',',$variables);
   	//get the headers
   	$headerRow = array_reverse($results[0]);
   	foreach($headerRow as $h){
   		if(!in_array($h,$variablesArray)){
   			$headers[] = $h;
   		} else {
   			//get the concept label
   			$label = $this->getVariableLabel($h);
   			$headers[] = str_replace('.','',$label->label);
   		}
   	}
   	
   	
   	$data = array();
   	$data[] = $headers;
   	for($x = 1; $x < count($results); $x++){
   			
   		$tmpData = array();
   		$row = array_reverse($results[$x]);
   		//get state and county values
   		for($i = 0; $i < count($row); $i++){
   			$header = $headerRow[$i];
   			$val = $row[$i];
   			if($header == 'state'){
   				$state = $val;
   			}
   			if($header == 'county'){
   				$county = $val;
   			}
   	
   		}
   			
   		for($i = 0; $i < count($row); $i++){
   			$header = $headerRow[$i];
   			$val = $row[$i];
   			if(in_array($header,$variablesArray)){
   				$tmpData[] = $val;
   			} else {
   				//get the geography value
   				if($header == 'state'){
   					$geo = $this->getGeographyState($state);
   					//$headers[] = $label->label;
   					if(isset($geo->NAME)){
   						$tmpData[] = $geo->NAME;
   					} else {
   						$tmpData[] = 'Vermont';
   					}
   					//$tmpData[] = 'Vermont';
   				} else if($header == 'county') {
   					$geo = $this->getGeographyCounty($state.$val);
   					//$headers[] = $label->label;
   					$tmpData[] = $geo->NAME;
   				}  else if($header == 'county subdivision') {
   					$geo = $this->getGeographyCountySubdivision($state.$county.$val);
   					//$headers[] = $label->label;
   					$tmpData[] = $geo->NAME;
   				} else {
   					$tmpData[] = 'United States';
   				}
   					
   			}
   	
   		}
   		$data[] = $tmpData;
   	}
   	return $data;
   }
   
   function cacheACSData($data){
       $id = $this->db->insert('acs_data_cache', $data);
       return $id;
   }
   
   function combineAcsData($state,$variables,$dataset,$year,$geography, $variableName, $isChart = null){
   	
   	$results = $this->getAcsData($variables,$dataset,$year,$geography);
   	
   	//echo 'combining '; print_r($results);exit;
   	
   	$variablesArray = explode(',',$variables);
   	
   	//print_r($variablesArray);
   	
   	$headerRow = array_reverse($results[0]);
   	
   	foreach($headerRow as $h){
   		if(!in_array($h,$variablesArray)){
   			$headers[] = $h;
   		}
   	}
   	$headers[] = $variableName;
   	
   	//echo 'header row';
   	//print_r($headerRow);
   	//print_r($headers); exit;
   	
   	$data = array();
   	$data[] = $headers;
   	
   	for($x = 1; $x < count($results); $x++){
   	    
   	    //for every result, get the geography values
   	
   		$tmpData = array();
   		$row = array_reverse($results[$x]);
   		//create a place to store the total value of all the variables combined
   		$rowTotalVal = 0;
   		//get state and county values
   		for($i = 0; $i < count($row); $i++){
   			$header = $headerRow[$i];
   			$val = $row[$i];
   			if($header == 'state'){
   				$state = $val;
   			}
   			if($header == 'county'){
   				$county = $val;
   			}
   	
   		}
   	
   		for($i = 0; $i < count($row); $i++){
   			$header = $headerRow[$i];
   			$val = $row[$i];
   			if(in_array($header,$variablesArray)){
   				//it's a variable, so add them together
   				$rowTotalVal += $val;
   			} else {
   				//get the geography value
   				if($header == 'state'){
   					$geo = $this->getGeographyState($state);
   					
   					if(isset($geo->NAME)){
   					    if($isChart == 1) { $tmpData['state'] = $geo->NAME; } else { $tmpData[] = $geo->NAME; } 
   					} else {
   					    if($isChart == 1) { $tmpData['state'] = 'Vermont'; } else { $tmpData[] = 'Vermont'; }
   					}
   					
   				} else if($header == 'county') {
   					$geo = $this->getGeographyCounty($state.$val);   					
   					if($isChart == 1) { $tmpData['county'] = $geo->NAME; } else { $tmpData[] = $geo->NAME; }
   				}  else if($header == 'county subdivision') {
   					$geo = $this->getGeographyCountySubdivision($state.$county.$val);  					
   					if($isChart == 1) { $tmpData['county+subdivision'] = $geo->NAME; } else { $tmpData[] = $geo->NAME; }
   				} else {
   				    if($isChart == 1) { $tmpData['country'] = 'United States'; } else { $tmpData[] = 'United States'; }
   				}
   				
   				
   			}
   	
   		}
   		if($isChart == 1) { $tmpData[$variableName] = $rowTotalVal; } else { $tmpData[] = $rowTotalVal; } ;
   		$data[] = $tmpData;
   	}
   	//echo 'combined data '; print_r($data); exit;
   	return $data;
   }
   
   function combineProcessedAcsData($acsData, $variables, $variableName){
       
       $results = $acsData;
       
       //echo 'combining '; print_r($results);
       
       //get a list of variable names, so we can identify which columns to combine
       foreach(explode(',',$variables) as $v){
           $label = $this->getVariableLabel($v);
           $variableNames[] = str_replace('.','',$label->label);
       }
       
       //print_r($variableNames); 

       $headerRow = $results[0];
       $nonVariableColumns = array('county', 'state', 'county+subdivision','county subdivision');
       foreach($headerRow as $h){
           //TODO: check if it's a variable column in some other way
           if(!in_array($h, $nonVariableColumns)) {
               //based on how we process the raw data by putting all the geographies first, 
               //if it's not one of the geographies, then everything else must be 
               $variablesArray[] = $h;
           } else {
               $headers[] = $h;
           }
       }
       $headers[] = $variableName;
       $data = array();
       $data[] = $headers;
       
       //print_r($data);
       
       //now we need to create a new results array, keyed by the headers
       
       for($x = 1; $x < count($results); $x++){
           $row =$results[$x];
           $tmpRow = array();
           //create a place to store the total value of all the variables combined
           $rowCombinedVal = 0;
           //for every value in the row, put into a temporarary array, keyed by a name rather than index position
           for($i = 0; $i < count($row); $i++){
                $key = $headerRow[$i];
                $rowVal = $row[$i];
                if(in_array($key, $variableNames)){
                    //it's a variable value, so just add it to the total
                    $rowCombinedVal += $rowVal;
                } else {
                    //it's not a variable to be combined, so just add the key and value to the tmp row
                    $tmpRow[$key] = $rowVal;
                }               
           }
           $tmpRow[$variableName] = $rowCombinedVal;
           $tmpRows[] = $tmpRow;
           //$data[] = $tmpData;
       }
       //print_r($headers);
       //print_r($tmpRows); exit;
       //now for each of the keyed rows, put them into a new dataset based on the order of the new headers
       foreach($tmpRows as $row){
           $tmpData = array();
           foreach($headers as $h){
               $tmpData[] = $row[$h];
           }
           $data[] = $tmpData;
       }
       
       //echo 'combined data '; print_r($data); exit;
       return $data;
   }
   
   function fetchAcsData($variables,$dataset,$year,$geography,$state = '50'){
       /*
        * This function fetches the raw ACS data from the census API
        */
       $API_KEY = '255c91c7e6da37cf41b65cb780dbca7eed5640b1';
       
       $acsYear = $year;
       $period = $dataset;
       $tableName = $variables;
       $geographyName = $geography;
       $state = $state;
       
       //exit;
       
       $URL = 'https://api.census.gov/data/'.
           $acsYear.'/acs/acs'.
           $period.'?key='.
           $API_KEY.'&get='.
           $tableName;
           
           if($geographyName == 'county'){
               $URL .= '&for='.
                   $geographyName.':*&in=state:'.
                   $state;
           } else if( $geographyName == 'county+subdivision'){
               $URL .= '&for='.
                   $geographyName.':*&in=state:'.
                   $state;
           } else if( $geographyName == 'place'){
               $URL .= '&for='.
                   $geographyName.':*&in=state:'.
                   $state;
           } else if( $geographyName == 'state'){
               $URL .= '&for='.
                   'state:*';
               //$state;
           } else {
               $URL .= '&for=us:*';
           }
           
           //echo $URL; exit;
           //check if the data has already been queried from the census API, and if so just 
           //pull it from the cache
           $cacheData = $this->getCachedACSData($URL);
           
           $ch = curl_init($URL);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           curl_setopt($ch, CURLOPT_VERBOSE, true);
           curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
           
           if($cacheData == '0'){
           
               $output = curl_exec($ch);
               $cData['api_endpoint'] = $URL;
               $cData['acs_data'] = $output;
               $this->cacheACSData($cData);
           
           } else {
               $output = $cacheData;
           }
           
           
           curl_close($ch);
           $dataArray = json_decode($output);
           
           return $dataArray;
       
   }
   
   function getAcsData($variables,$dataset,$year,$geography,$state = '50'){
   	
   	//print_r($v); 
   	
   	//define('API_KEY', '255c91c7e6da37cf41b65cb780dbca7eed5640b1');
   	
    $API_KEY = '255c91c7e6da37cf41b65cb780dbca7eed5640b1';
   	
   	$acsYear = $year;
   	$period = $dataset;
   	$tableName = $variables;
   	$geographyName = $geography;
   	$state = $state;
   	
   	//exit;
   	
   	$URL = 'https://api.census.gov/data/'.
   			$acsYear.'/acs/acs'.
   			$period.'?key='.
   			$API_KEY.'&get='.
   			$tableName;
   	
   	if($geographyName == 'county'){
   		$URL .= '&for='.
   				$geographyName.':*&in=state:'.
   				$state;
   	} else if( $geographyName == 'county+subdivision'){
   		$URL .= '&for='.
   				$geographyName.':*&in=state:'.
   				$state;
   	} else if( $geographyName == 'place'){
   		$URL .= '&for='.
   				$geographyName.':*&in=state:'.
   				$state;
   	} else if( $geographyName == 'state'){
   		$URL .= '&for='.
   				'state:*';
   		//$state;
   	} else {
   		$URL .= '&for=us:*';
   	}
   	
   	//echo $URL; exit;
   	
   	//check if the data has already been queried from the census API, and if so just
   	//pull it from the cache
   	$cacheData = $this->getCachedACSData($URL);
   	
   	$ch = curl_init($URL);
   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   	curl_setopt($ch, CURLOPT_VERBOSE, true);
   	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   	
   	if($cacheData == '0'){
   	    
   	    $output = curl_exec($ch);
   	    $cData['api_endpoint'] = $URL;
   	    $cData['acs_data'] = $output;
   	    $this->cacheACSData($cData);
   	    
   	} else {
   	    $output = $cacheData;
   	}
   	
   	curl_close($ch);
   	$dataArray = json_decode($output);
   	   	
   	return $dataArray;
   	
   	
   }
   
   function getCachedACSData($url){
       
       $acsData = '0';
       $this->db->select('acs_data');
       $this->db->from('acs_data_cache');
       $this->db->where('api_endpoint',$url);
       $query = $this->db->get();
       $results = $query->result();
       if(isset($results[0])){
           $acsData = $results[0]->acs_data;
       } 
       return $acsData;
   }
   
   function getFipsFromGeography($geoType, $geo){
      $tableName = '';
      if($geoType == 'state'){
          $tableName = 'gaz_states';
      }
       $retVal = array();
       $this->db->select('GEOID');
       $this->db->from($tableName);
       $this->db->where('USPS',$geo);
       $query = $this->db->get();
       $results = $query->result();
       if(isset($results[0])){
           $retVal = $results[0];
       }
       return $retVal;

   }

   function getGeographyCounty($geoid){
   	$this->db->select('NAME');
   	$this->db->from('gaz_counties');
   	$this->db->where('GEOID',$geoid);
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results[0];
   }
   
   function getGeographyCountySubdivision($geoid){
   	$this->db->select('NAME');
   	$this->db->from('gaz_county_subdivisions');
   	$this->db->where('GEOID',$geoid);
   	$query = $this->db->get();
   	$results = $query->result();
   	if(isset($results[0])){
   	return $results[0];
   	} else { return ''; }
   }
   
   function getGeographyState($geoid){
   	$retVal = array();
   	$this->db->select('NAME');
   	$this->db->from('gaz_states');
   	$this->db->where('GEOID',$geoid);
   	$query = $this->db->get();
   	$results = $query->result();
   	if(isset($results[0])){
   		$retVal = $results[0];
   	}
   	return $retVal;
   }
   
   function getGeoMap($geoGroup){
   	
   			//$sql = "SELECT NAME, ahs_district, bbf_region FROM gaz_county_subdivisions cs
				//join gaz_geo_map gm on gm.town_village_geoid = cs.GEOID
				//where USPS = 'VT'";
   			
   			$sql = "SELECT NAME, geography_map_name as '".$geoGroup."'
   			from gaz_geography_map join gaz_county_subdivisions
   			on gaz_geography_map.gaz_county_subdivision = gaz_county_subdivisions.GEOID
   			where geography_map_type = '".$geoGroup."'";
   			
   			$query = $this->db->query($sql);
   			$results = $query->result();
   			//print_r($results); exit;
   			//put the results into an array keyed on the town - currently this is the only supported roll up
   			foreach($results as $r){
   				$retVal[$r->NAME] = $r->$geoGroup;
   			}
   			return $retVal;

   }
   
   function getMedianIncome($geo, $geoVal){
      $retVal = array();
      if($geo == 'zip'){
          $this->db->select("*");
          $this->db->from('medianincomes');
          $this->db->where('zip',$geoVal);
          $query = $this->db->get();
          $results = $query->result();
          if(isset($results[0])){
              $retVal =  $results[0];
          }
      }
      return $retVal;
   }

   function groupAcsData($data,$geoGroup, $isChart = null){
   		
   	$geoMap = $this->getGeoMap($geoGroup);
   	$groupData = array();
   	
   	
   	$headers = $data[0];
   	$headerRow = array();
   	$headerRow[] = $geoGroup;
   	//create new headers
   	for($x = 0; $x < 1; $x++){
   		$row = $data[$x];
   		//get the town to key on
   		for($i = 0; $i < count($row); $i++){
   			$header = $row[$i];
   			if($header != 'county subdivision' && $header != 'county' && $header != 'state' ){
   				$headerRow[] = $header;
   			}
   		}  		 
   	}
   	
   	//print_r($headers); 
    //echo $geoGroup; 
   	//print_r($data); 
   	//exit;
   	$groupData[] = $headerRow;
   	
   	$dataVals = array();
   	
   	//if($isChart == null){
   	for($x = 1; $x < count($data); $x++){
   	    //get each row of data, skipping the first row which are headers
   		$row = $data[$x];
   		//print_r($row);
   		
   		//get the geography group
   		for($i = 0; $i < count($row); $i++){
   			$header = $headers[$i];
   			if($header == 'county subdivision'){
   			    if($isChart){ $val = $row['county+subdivision']; } else { $val = $row[$i]; } 
   				$geo = $geoMap[$val];
   			} 
   		}
   		
   		//put all the values into the geography array
   		for($i = 0; $i < count($row); $i++){
   			$header = $headers[$i];
   			
   			if($header != 'county subdivision' && $header != 'county' && $header != 'state'){
   			    if($isChart){ $val = $row[$header]; } else { $val = $row[$i]; }
   				$dataVals[$geo][$header][] = $val;
   			}
   		}
   		
   	}
   	
   	//}
   	
   	
   	
   	//print_r($dataVals); exit;
   		//now total the variables by geography
   		foreach($dataVals as $geo => $variables){
   			foreach($variables as $variable => $values){
   				$totalVal = 0;
   				foreach($values as $v){
   					$totalVal += $v;
   				}
   				$dataVals[$geo][$variable] = $totalVal;
   			}
   		}
   		//print_r($dataVals); exit;
   		foreach($dataVals as $geo => $variables){
   			$tmpData = array();
   			$tmpData[$geoGroup] = $geo;
   			
   			foreach($variables as $key => $variable){
   				$tmpData[$key] = $variable;
   			}
   			$groupData[] = $tmpData;
   		}
   		//print_r($groupData); exit;
   	
   	return $groupData;
   	
   }

   function groupProcessedAcsData($data,$geoGroup){
       
       $geoMap = $this->getGeoMap($geoGroup);
       $results = $data;
       $headerRow = $results[0];
       
       //first created a transformed data set with values keyed by name rather than index
       $keyedData = array();
       for($x = 1; $x < count($results); $x++){
           $row =$results[$x];
           $tmpRow = array();
           //for every value in the row, put into a temporarary array, keyed by a name rather than index position
           for($i = 0; $i < count($row); $i++){
               $key = $headerRow[$i];
               $rowVal = $row[$i];
               $tmpRow[$key] = $rowVal;
           }
           $keyedData[] = $tmpRow;
       }
       
       //return $keyedData;
       $groupData = array();
       
       
       $nonVariableColumns = array('county', 'state', 'county+subdivision','county subdivision');
       //since geographies are first, add the new grouped geography, and then just all the other values
       //in the first row to the new headers row
       $headers[] = $geoGroup;
       foreach($headerRow as $h){
           //TODO: check if it's a variable column in some other way
           if(!in_array($h,$nonVariableColumns)){
                $headers[] = $h;
            }

       }
       
       //return $headers;
       
       $groupData[] = $headers;
             
       $dataVals = array();
       
       //get each row of keyed data
       foreach($keyedData as $row){         
           //get the geography group
           $geo = $geoMap[$row['county subdivision']];          
           //put all the values into the geography array
           foreach($row as $key => $val){
               if(!in_array($key,$nonVariableColumns)){
                   $dataVals[$geo][$key][] = $val;
               }
           }     
       }
        
       //return $dataVals;

       //now total the variables by geography
       foreach($dataVals as $geo => $variables){
           foreach($variables as $variable => $values){
               $totalVal = 0;
               foreach($values as $v){
                   $totalVal += $v;
               }
               $dataVals[$geo][$variable] = $totalVal;
           }
       }
      //return $dataVals;
       //print_r($dataVals); exit;
       foreach($dataVals as $geo => $variables){
           $tmpData = array();
           $tmpData[$geoGroup] = $geo;
           
           foreach($variables as $key => $variable){
               $tmpData[$key] = $variable;
           }
           $groupData[] = $tmpData;
       }
       //print_r($groupData); exit;
       
       //*** if the data is going to be drawn to a table, we need to put it in a non-keyed array of rows, with the 
       // columns in the same order as the headers, e.g.
       // ["county subdivision", "county", "state", "Estimate!!Total", "Estimate!!Total!!Under 18 years"]
       // ["Searsburg town", "Bennington County", "Vermont", "110", "11"]
      
       $tableData = array();
       $tableData[] = $headers;
       for($x = 1; $x < count($groupData); $x++){
           $d = $groupData[$x];
           $tmpRow = array();
           foreach($headers as $h){
               $tmpRow[] = $d[$h];
           }
           $tableData[] = $tmpRow;
       }
       //before we return the table data, change the geography group key to a more friendly label
       $tableHeader = array();
       foreach($headers as $h){
           if($h == $geoGroup){
               //get the report label
               $headerLabel = $this->getGeographyGroupLabel($h);
               $tableHeader[] = $headerLabel;
           } else {
               $tableHeader[] = $h;
           }
       }
       $tableData[0] = $tableHeader;
       
       //return $groupData;
       return $tableData;
      
       
   }
   
   function getGeographyGroupLabel($h){
       $this->db->select("geography_map_label");
       $this->db->from('gaz_geography_map');
       $this->db->where('geography_map_type',$h);
       $this->db->limit(1);
       $query = $this->db->get();
       $results = $query->result();
       return $results[0]->geography_map_label; 
   }
   
   function getGeographyMapsForUser($userId){
       $this->db->select("geography_map_label, geography_map_type");
       $this->db->from('gaz_geography_map');
       $this->db->where('geography_map_owner',$userId);
       $this->db->distinct();
       $query = $this->db->get();
       $results = $query->result();
       return $results; 
   }
      
   function getReport($reportId = null){
    $this->db->select("*");
   	$this->db->from('acs_dataset');
   	$this->db->where('id',$reportId);
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results[0];
    }

/**
 * Get Census report data to be used for generating tables and visualizations
 *
 * @param       int  $reportId The ID of the saved report    
 * @return      array
 */
   function getReportChartData($reportId, $denomId = null){
        $report = $this->getReport($reportId);
        if($denomId != null){
            $denominator = $this->getReport($denomId);
        }
        //print_r($denominator); exit;
        $data['report'] = $report;
        //get the data based on the type of report
        //$data = array();
        $variables = $report->variables;
        $dataset = $report->dataset;
        
        //******************  GET MAIN REPORT DATA FIRST ****************************
        //if the saved report uses the most recent data available, find what year that is
        if(isset($report->use_most_recent) && $report->use_most_recent == 1){
            $year = '2019';
        } else {
            $year = $report->year;
        }
        
        $geography = $report->geography;
        $variableName = $report->combine_name;
        $state = $report->state;
        $geoGroup = $report->geo_group;
        
        
        //see if the data has been combined
        if(isset($report->combine_name) && $report->combine_name != null && $report->combine_name != ''){
            $dataCombined = '1';
        } else {
            $dataCombined = '0';
        }

        
        $this->load->model('Census_model');
        
        //$acsData = $this->getAcsData($variables,$dataset,$year,$geography,$state = '50');
        //print_r($acsData); 
        
        if($dataCombined == '1'){
            //echo $state.' : '.$variables.' : '.$dataset.' : '.$year.' : '.$geography.' : '.$variableName; exit;
            $reportData = $this->combineAcsData($state,$variables,$dataset,$year,$geography,$variableName,1);
        } else {
            $reportData = $this->acsData($state,$variables,$dataset,$year,$geography);
        }
        
       //print_r($reportData); exit;
        
        if(isset($report->geo_group) && $report->geo_group != null && $report->geo_group != '' && $report->geo_group != 'none'){
            $reportData = $this->groupAcsData($reportData ,$geoGroup,1);
        }
        //******************  END MAIN REPORT DATA ****************************
        
        //echo 'report data: '; print_r($reportData);exit;
        
        if(isset($denominator)){
            
            //******************  GET DENOMINATOR REPORT DATA ****************************
            //if the saved report uses the most recent data available, find what year that is
            if(isset($denominator->use_most_recent) && $denominator->use_most_recent == 1){
                $dyear = '2019';
            } else {
                $dyear = $denominator->year;
            }
            $dvariables = $denominator->variables;
            $ddataset = $denominator->dataset;
            $dgeography = $denominator->geography;
            $dvariableName = $denominator->combine_name;
            $dstate = $denominator->state;
            $dgeoGroup = $denominator->geo_group;
            $ddData = array();
            
            //see if the data has been combined
            if(isset($denominator->combine_name) && $denominator->combine_name != null && $denominator->combine_name != ''){
                $ddataCombined = '1';
            } else {
                $ddataCombined = '0';
            }
            
            $this->load->model('Census_model');
            
            if($ddataCombined == '1'){
                $dData = $this->combineAcsData($dstate,$dvariables,$ddataset,$dyear,$dgeography,$dvariableName,1);
            } else {
                $dData = $this->acsData($dstate,$dvariables,$ddataset,$dyear,$dgeography);
            }
            
            //print_r($reportData); exit;
            
            if(isset($denominator->geo_group) && $denominator->geo_group != null && $denominator->geo_group != '' && $denominator->geo_group != 'none'){
                $dData = $this->groupAcsData($dData ,$dgeoGroup,1);
            }
            //******************  END DENOMINATOR REPORT DATA ****************************
        }
        //print_r($reportData);
        //print_r($dgeoGroup); exit;
        
        //if there is a denominator, calculate the percent values rather than the population count
        if(isset($dData) && !empty($dData) && count($dData) > 0){
            //print_r($dData); 
            $dataVals = array();
            //first get the denominator values by the geography
            foreach($dData as $d){
                //if it's a geography group, we need to use that as a key to find the values
                if(isset($dgeoGroup) && $dgeoGroup != null && $dgeoGroup != '' && $dgeoGroup != 'none'){
                    if(array_key_exists($dgeoGroup, $d) && array_key_exists($dvariableName, $d)){
                        $dataVals[$d[$dgeoGroup]] = $d[$dvariableName];
                    }
                } else {
                    if(array_key_exists($dgeography, $d) && array_key_exists($dvariableName, $d)){
                        $dataVals[$d[$dgeography]] = $d[$dvariableName];
                    }
                }
                
                
            }
            //echo 'Data Values: ';print_r($dataVals);exit;
            //now replace the counts with the percent
            //print_r($reportData); exit;
            foreach($reportData as $r){
                //account for whether there is a geography grouping aside from the standard ACS/census geographies
                if(isset($dgeoGroup) && $dgeoGroup != null && $dgeoGroup != '' && $dgeoGroup != 'none'){
                    if(isset($r[$variableName]) && isset($r[$dgeoGroup])){
                        $num = $r[$variableName];
                        $denom = $dataVals[$r[$dgeoGroup]];
                        $pct = ($num/$denom) * 100;
                        $r[$variableName] = number_format($pct,1);
                    }
                    $percentData[] = $r;
                } else {
                    if(isset($r[$variableName]) && isset($r[$geography])){
                        $num = $r[$variableName];
                        $denom = $dataVals[$r[$geography]];
                        $pct = ($num/$denom) * 100;
                        $r[$variableName] = number_format($pct,1);
                    }
                    $percentData[] = $r;
                }
                
                
                
            }
            $reportData = $percentData;
        }

        //print_r($percentData); exit;
        //print_r($dData);
        //print_r($reportData);
        //exit;
        //$gdata = $this->Census_model->groupAcsData($data,$geoGroup);
        $data['reportData'] = $reportData;
        
        //convert the multi-dimensional array into an array of objects
        $retVal = array_map(function($element) {
            return (object) $element;
        }, $reportData);
        
        return $reportData;
        
    }

   function getReportData($reportId){

        $report = $this->getReport($reportId);
		$data['report'] = $report;
		//get the data based on the type of report
		//$data = array();
		$variables = $report->variables;
		$dataset = $report->dataset;
		
	    //print_r($report); exit;

		//if the saved report uses the most recent data available, find what year that is
		if(isset($report->use_most_recent) && $report->use_most_recent == 1){
			$year = '2019';
		} else {
			$year = $report->year;
		}
		
		$geography = $report->geography;
		$variableName = $report->combine_name;
		$state = $report->state;
		$geoGroup = $report->geo_group;

		//see if the data has been combined
		if(isset($report->combine_name) && $report->combine_name != null && $report->combine_name != ''){
			$dataCombined = '1';
		} else {
			$dataCombined = '0';
		}
				
		$this->load->model('Census_model');
		if($dataCombined == '1'){
			$reportData = $this->combineAcsData($state,$variables,$dataset,$year,$geography,$variableName);
		} else {
			$reportData = $this->acsData($state,$variables,$dataset,$year,$geography);
		}
		
		

		if(isset($report->geo_group) && $report->geo_group != null && $report->geo_group != '' && $report->geo_group != 'none'){
			$reportData = $this->groupAcsData($reportData ,$geoGroup);
		}
		//print_r($reportData); 
		//$gdata = $this->Census_model->groupAcsData($data,$geoGroup);
		//$data['reportData'] = $reportData;

		//get the data into a visual
		//print_r($reportData); exit;
		$chartData['chart_type'] = 'bar';
		$chartData['chart_title'] = $report->name;
		$chartData['chart_sub_title'] = '';
		$chartData['id'] = $report->id;
		//first build the label array
		$labels = array();
		$cdata = array();
		$datasets = array();
		
		$count = 0;
		foreach($reportData as $d){
		    if(isset($geoGroup) && $geoGroup != null && $geoGroup != '' && $geoGroup != 'none'){
    			if($count > 0){
    				$labels[] = $d[$geoGroup];
    				$cdata[] = $d[$variableName];
    			}
    			$count ++;
		    } else {
		        if($count > 0){
		            $labels[] = $d[0];
		            $cdata[] = $d[1];
		        }
		        $count ++;
		    }
			
		}
		$chartData['labels'] = $labels;
		//print_r($labels); exit;

		//currently only one dataset - for our default, datasets are determined by year
		$dataset = array();
		$dataset['label'] = $year;
		$dataset['data'] = $cdata;
		$dataset['backgroundColor'] = '#ee2d67';
		$dataset['borderColor'] = '#ee2d67';
		$dataset['fill'] = '';
		$datasets[] = $dataset;
		$chartData['datasets'] = $datasets;
		//print_r($chartData); exit;

		$data['dc'] = $chartData;

        return $data;
   }
   
   function getReportTableData($reportId){
       
       $report = $this->getReport($reportId);
       $data['report'] = $report;
       //get the data based on the type of report
       //$data = array();
       $variables = $report->variables;
       $dataset = $report->dataset;
       
       //print_r($report); exit;
       
       //if the saved report uses the most recent data available, find what year that is
       if(isset($report->use_most_recent) && $report->use_most_recent == 1){
           $year = '2019';
       } else {
           $year = $report->year;
       }
       
       $geography = $report->geography;
       $variableName = $report->combine_name;
       $state = $report->state;
       $geoGroup = $report->geo_group;
       
       //see if the data has been combined
       if(isset($report->combine_name) && $report->combine_name != null && $report->combine_name != ''){
           $dataCombined = '1';
       } else {
           $dataCombined = '0';
       }
       $reportData = $this->fetchAcsData($variables,$dataset,$year,$geography,$state);
       $reportData = $this->processRawAcsData($reportData);
       //print_r($reportData); exit;
       if($dataCombined == '1'){
           //$reportData = $this->combineAcsData($state,$variables,$dataset,$year,$geography,$variableName);
           $reportData = $this->combineProcessedAcsData($reportData,$variables, $variableName);
       }      
       if(isset($report->geo_group) && $report->geo_group != null && $report->geo_group != '' && $report->geo_group != 'none'){
           $reportData = $this->groupProcessedAcsData($reportData,$geoGroup);
       }
       return $reportData; 
       
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
   
   function getVariableLabel($variable){
   	$this->db->select('label');
   	$this->db->from('acs_variables');
   	$this->db->where('variable',$variable);
   	$query = $this->db->get();
   	$results = $query->result();
   	//print_r($results); exit;
   	//echo $this->db->last_query(); exit;
   	return $results[0];
   }
   
   function processRawAcsData($results){
       
       //get the headers
       //Census data comes back from the API as a list of all the variable values, with the geographies at the end.  We 
       //can figure out what the variables are by iterating the first/header row of data, and looking for any that are not 
       //one of the standard geographic headings.  Ultimately we probably want to load up all the variables into an array, 
       //and check against that.  We reverse the array, because we want the returned data in a format that 
       //we can write out to a basic table
       $headerRow = array_reverse($results[0]);
       $nonVariableColumns = array('county', 'state', 'county+subdivision','county subdivision');
       foreach($headerRow as $h){
           //TODO: check if it's a variable column in some other way
           if(!in_array($h, $nonVariableColumns)) {
               $variablesArray[] = $h;
               //get the concept label
               $label = $this->getVariableLabel($h);
               $headers[] = str_replace('.','',$label->label);
           } else {
               $headers[] = $h;
               
           }
       } 
       $data = array();
       $data[] = $headers;
       for($x = 1; $x < count($results); $x++){
           
           $tmpData = array();
           $row = array_reverse($results[$x]);
           //get state and county values
           for($i = 0; $i < count($row); $i++){
               $header = $headerRow[$i];
               $val = $row[$i];
               if($header == 'state'){
                   $state = $val;
               }
               if($header == 'county'){
                   $county = $val;
               }
               
           }
           
           for($i = 0; $i < count($row); $i++){
               $header = $headerRow[$i];
               $val = $row[$i];
               if(in_array($header,$variablesArray)){
                   $tmpData[] = $val;
               } else {
                   //get the geography value
                   if($header == 'state'){
                       $geo = $this->getGeographyState($state);
                       //$headers[] = $label->label;
                       if(isset($geo->NAME)){
                           $tmpData[] = $geo->NAME;
                       } else {
                           $tmpData[] = 'Vermont';
                       }
                       //$tmpData[] = 'Vermont';
                   } else if($header == 'county') {
                       $geo = $this->getGeographyCounty($state.$val);
                       //$headers[] = $label->label;
                       $tmpData[] = $geo->NAME;
                   }  else if($header == 'county subdivision') {
                       $geo = $this->getGeographyCountySubdivision($state.$county.$val);
                       //$headers[] = $label->label;
                       $tmpData[] = $geo->NAME;
                   } else {
                       $tmpData[] = 'United States';
                   }
                   
               }
               
           }
           $data[] = $tmpData;
       }
       return $data;
   }
   
   function saveAcsDataset($data){
   		//print_r($data); exit;
   		$id = $this->db->insert('acs_dataset', $data);
   		return $id;
   }
   
   function saveGeographyMap($data){
       $id = $this->db->insert('gaz_geography_map', $data);
       return $id;
   }
   
   function searchConcepts($searchTxt){
   	
   	$this->db->select('concept,group');
   	$this->db->from('acs_variables');
   	$this->db->like('concept',$searchTxt);
   	$this->db->order_by('concept');
   	$this->db->distinct();
   	$query = $this->db->get();
   	$results = $query->result();
   	//print_r($results); exit;
   	//echo $this->db->last_query(); exit;
   	return $results;
   	
   }
   
   function searchConceptVariables($concept){
   
   	$this->db->select('concept, variable, label');
   	$this->db->from('acs_variables');
   	$this->db->where('concept',$concept);
   	$this->db->order_by('variable');
   	$query = $this->db->get();
   	$results = $query->result();
   	//print_r($results); exit;
   	//echo $this->db->last_query(); exit;
   	return $results;
   
   }
     
   
}