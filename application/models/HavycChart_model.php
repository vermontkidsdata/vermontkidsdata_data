<?php
class HavycChart_model extends CI_Model {

  public function __construct()
   {
      $this->load->database();
   }
   
   function createChart($data = array()){
   	$this->db->insert('havyc_chart', $data);
   	return $this->db->insert_id();
   }
   
   function deleteChartDataset($datasetId){
   	$this->db->delete('havyc_chart_dataset', array( 'id' => $datasetId));
   }
   
   function updateChart($chartId,$data = array()){
   //print_r($data); print_r($chartId); exit;
   	$this->db->update('havyc_chart', $data, array("id" => $chartId));
   }
   
   function addChartDataset($data = array()){
   	//print_r($data); exit;
   	$this->db->insert('havyc_chart_dataset', $data);
   	return $this->db->insert_id();
   }
   
   function getChart($chartId){
   	$retVal = array();
   	$this->db->select('*');
   	$this->db->from('havyc_chart');
   	$this->db->where('id',$chartId);
   	$query = $this->db->get();
   	$results = $query->result();
    //print_r($results[0]); exit;
   	if(isset($results[0])){ 
   		$retVal = $results[0]; 
   	}
   //print_r($retVal); exit;
   	return $retVal;
   }
   
   function getChartDatasets($chartId){
   	$this->db->select('*');
   	$this->db->from('havyc_chart_dataset');
   	$this->db->where('chart_id',$chartId);
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
   
   function getBarChart($chartId){
   
   	$chartData = array();
   	
    //***************************************************************** 		
   	    //get the meta data for the chart

   	$chart = $this->getChart($chartId);
       //echo $chartId; exit;
   	//print_r($chart);exit;
   	//echo $chart->where_clause;
   	
   	$chartData["chart_type"] = $chart->chart_type;
   	$chartData["id"] = $chart->id;
   	
   	$chartData["chart_title"] = $chart->chart_title;
   	$chartData["chart_sub_title"] = $chart->chart_sub_title;
   	$chartData["y_data_type"] = $chart->y_data_type;
   	$chartData["x_data_type"] = $chart->x_data_type;
   	$chartData["y_min"] = $chart->y_min;
   	$chartData["y_max"] = $chart->y_max;
    $chartData["show_datalabels"] = $chart->show_datalabels;
    $chartData["show_legend"] = $chart->show_legend;
    // the column names are from legacy data structers.  Commented for more meaningful definitions - also 
    // multiple variables are set for names to be a bit more intuitive
   	$chartLabelColumn = $chart->label_column;
    $chartSeriesColumn = $chart->label_column; // used to break up the results into multiple series/datasets
   	$chartDataColumn = $chart->data_column;
    $chartDataLabelColumn = $chart->data_label_column;
   	$labelColumn = $chart->labels;
    //***************************************************************** 

    //***************************************************************** 
    //determine the data source, and get the results
   	$sql = $chart->data_query;
   	if(($sql == null || $sql == '') && $chart->chart_datasource != 'api' && $chart->chart_datasource != 'census'){
   		
   			$dataset = $this->getDataset($chart->dataset);
   			$sql = 'select * from '.$dataset->data_table.' where is_current = 1 ';
   			if(isset($chart->where_clause)){
   				$where = $chart->where_clause;
   				$sql .= $where.' ';
   			}
   			if($chart->order_column != null && $chart->order_column != ''){
   				$sql .= ' order by '.$chart->order_column;
   			}
   	}
   
    if($sql != null && $sql != ''){
        $query = $this->db->query($sql);   	
   	    $results = $query->result();
    } else {
        if($chart->chart_datasource == 'api'){
            $this->load->helper('rest');
            $results = json_decode(make_request($chart->api_endpoint));
        }
        if($chart->chart_datasource == 'census'){
            $this->load->helper('rest');
            //$results = array();
            $this->load->model('Census_model');
            $censusResults = $this->Census_model->getReportChartData($chart->dataset, $chart->dataset_denominator);
            //$results = json_decode(make_request($chart->api_endpoint));
            
            $results = $censusResults;
        }
    }

   //print_r($results); exit;
   
 //***************************************************************** 

 //***************************************************************** 
   	$labels = array();
   	$datasets = array();
    //GENERATE THE LABELS
    //Need to treat the census data differently since it comes back as a multi-dimensional array and not an 
    //array of objects. This is due to the fact that combined variable names do not map well to object properties, but
    //are fine as array keys
   	if($chart->chart_datasource == 'census'){ 
   	    
   	    foreach($results as $r){
   	        if(isset($r[$labelColumn])){
   	            $labels[]=$r[$labelColumn];
   	        }
   	    }
   	    
   	} else {
        //if we are mapping the resultset to the datasets, do that 
        if(isset($chart->map_query) && $chart->map_query == 1){
            //echo 'generating labels';
            
                foreach($results as $r){
           		        if(!in_array($r->$labelColumn,$labels)){
           			        $labels[]=$r->$labelColumn;
           		        }
           	     }
            
       	     
        } else {
       	
            if(isset($r->$labelColumn)){
       	        foreach($results as $r){
       		        if(!in_array($r->$labelColumn,$labels)){
       			        $labels[]=$r->$labelColumn;
       		        }
       	        }
            }
    
        }
    
   	} 
    
    
    //$labels = array_unique($labels);
   	$chartData["labels"] = $labels;
    //print_r($labels); 
    //exit;
    //*********************************************************


    //*********************************************************
   	//get the datasets for the chart if there are any... if not then try to build them from the configuration
   
   $chartDatasets = $this->getChartDatasets($chartId);

   //print_r($chartDatasets);exit;   
   	//build an array of y values   
   	//print_r($chartLabelColumn); print_r($chartDataColumn); exit;
   $datasetData = array();
   	//data need to be in the same order as the labels
    if($chart->chart_type == 'bar' || $chart->chart_type == 'horizontal bar'){
    //same deal with treating census data as multi-dimensional array
    if($chart->chart_datasource == 'census'){ 
        
    } else {
        if(isset($chartLabelColumn) && $chartLabelColumn != ''){
            foreach($results as $r){
                $datasetData[$r->$labelColumn][$r->$chartLabelColumn] = $r->$chartDataColumn;
       	    }
        }    
    }

    //if the chart is configured to map the query to the datasets automatically, do that.  otherwise
    //try to build the datasets from the chart configuration

    //print_r($chart); 

    if($chart->map_query == '1'){
    
       //echo 'mapping'; exit;
       $chartDatasets = array();
       $colors = $this->getColorsVKD();
       //if there is a series column, get the unique values of that series
       $seriesColumns = array();
       if(isset($chartSeriesColumn) && $chartSeriesColumn != '' && $chartSeriesColumn != null){
            foreach($results as $r){
                if(!in_array($r->$chartSeriesColumn,$seriesColumns)){
                    $seriesColumns[] = $r->$chartSeriesColumn;
                }
            }
    
             //print_r($chartDataColumn);
             //print_r($chartLabelColumn);
             //print_r($labels);
             //print_r($seriesColumns); 
             //print_r($results); echo '<br><br>'; 
            
             //get the data for each series into its own dataset
             $cnt = 0;
             foreach($seriesColumns as $s){
                $cd = new stdClass();
                $cd->label = $s;
                $cd->background_color = $colors[$cnt];
       		    $cd->border_color =  $colors[$cnt];
                $cd->border_width = "1";
       		    $cd->fill = 1;
                $data = array();
                $dataLabels = array();
                
                foreach($results as $r){
                    //if the value in the series column matches the current series, add the data
                    if($r->$chartSeriesColumn == $s){
                        $data[] = $r->$chartDataColumn;
                        if(isset($chartDataLabelColumn) && $chartDataLabelColumn !=null && $chartDataLabelColumn !=''){
                            $dataLabels[] = $r->$chartDataLabelColumn; 
                        }
                    }
                    
                }
       		    
       		    $cd->data = $data;
                $cd->data_labels = $dataLabels;
       		    $cnt ++;
       		    $chartDatasets[] = $cd; 
             }
             //print_r($chartDatasets); exit;
    
       } else {
    
       //just one series, so we can just iterate through all the results once
       //bulid the datasets from the labels
            
            //Example of building one dataset from the entire result set
            $cd = new stdClass();
            $data = array();
            $dataLabels = array();
            $cnt = 0;
            //print_r($results);exit;
            foreach($results as $r){
                if($chart->chart_datasource == 'census'){
                    if(isset($r[$chartDataColumn])){
                        $data[] = $r[$chartDataColumn];
                        if(isset($chartDataLabelColumn) && $chartDataLabelColumn !=null && $chartDataLabelColumn !=''){
                            $dataLabels[] = $r[$chartDataLabelColumn]; 
                        }
                    }
                } else {
                    $data[] = $r->$chartDataColumn;
                    if(isset($chartDataLabelColumn) && $chartDataLabelColumn !=null && $chartDataLabelColumn !=''){
                        $dataLabels[] = $r->$chartDataLabelColumn;
                    }                   
                }
                
                
                $cnt ++;
            }
       		$cd->label = '';
       		$cd->data = $data;
            $cd->data_labels = $dataLabels;
       		$cd->background_color = $colors;
       		$cd->border_color = $colors;
            $cd->border_width = "1";
       		$cd->fill = 1;
       		$chartDatasets[] = $cd;   
        }

    } else { 

       	
   	    foreach($chartDatasets as $cd){
   		    $data = array();
   		    foreach($datasetData as $key => $val){
   			    if(isset($val[$cd->label])){
	   		    $data[] = $val[$cd->label];
   			    } else {
   				    $data[]=null;
   			    }
   		    }
   		    $cd->data = $data;
	       }
   		
	 } 
	   	
 } 
 
 //print_r($chartDatasets); exit;
   
   	$dataDatasets = array();
   	foreach($chartDatasets as $cd){
   		$dArray = array();
   		$dArray['label'] = $cd->label;
   		$dArray['data'] = $cd->data;
        if(isset($cd->data_labels)){ $dArray['dataLabels'] = $cd->data_labels; }
   		$dArray['backgroundColor'] = $cd->background_color;
   		$dArray['borderColor'] = $cd->border_color;
        if(isset($cd->border_width)){ $dArray['borderWidth'] = $cd->border_width; }
   		if($cd->fill == 0){
   			$dArray['fill'] = false;
   		} else {
   			$dArray['fill'] = true;
   		}
   		$dataDatasets[] = $dArray;
   		 
   	}

    //print_r($dataDatasets); exit;
   	$chartData["datasets"] = $dataDatasets;
    //print_r($chartData); exit;
   	//print_r(json_encode($chartData["datasets"])); exit;
   	return $chartData;
   
   }

   function getStackedBarChart($chartId){
   
   	$chartData = array();
   	
    //***************************************************************** 		
   	//get the meta data for the chart

   	$chart = $this->getChart($chartId);   	
   	$chartData["chart_type"] = $chart->chart_type;
   	$chartData["id"] = $chart->id;
   	
   	$chartData["chart_title"] = $chart->chart_title;
   	$chartData["chart_sub_title"] = $chart->chart_sub_title;
   	$chartData["y_data_type"] = $chart->y_data_type;
   	$chartData["x_data_type"] = $chart->x_data_type;
   	$chartData["y_min"] = $chart->y_min;
   	$chartData["y_max"] = $chart->y_max;
    $chartData["show_datalabels"] = $chart->show_datalabels;
    $chartData["show_legend"] = $chart->show_legend;
   	$chartLabelColumn = $chart->label_column;
    $chartSeriesColumn = $chart->label_column; // used to break up the results into multiple series/datasets
   	$chartDataColumn = $chart->data_column;
    $chartDataLabelColumn = $chart->data_label_column;
   	$labelColumn = $chart->labels;
    //***************************************************************** 

    //***************************************************************** 
    //determine the data source, and get the results
   	$sql = $chart->data_query;
   	if(($sql == null || $sql == '') && $chart->chart_datasource != 'api'){
   		
   			$dataset = $this->getDataset($chart->dataset);
   			$sql = 'select * from '.$dataset->data_table.' where is_current = 1 ';
   			if(isset($chart->where_clause)){
   				$where = $chart->where_clause;
   				$sql .= $where.' ';
   			}
   			if($chart->order_column != null && $chart->order_column != ''){
   				$sql .= ' order by '.$chart->order_column;
   			}
   	}
   
    if($sql != null && $sql != ''){
        $query = $this->db->query($sql);   	
   	    $results = $query->result();
    } else {
        if($chart->chart_datasource == 'api'){
            //console.log('calling api');
            $this->load->helper('rest');
            $results = json_decode(make_request($chart->api_endpoint));
        }
    }

   //print_r($results);exit;
   
 //***************************************************************** 

 //***************************************************************** 
   	$labels = array();
   	$datasets = array();
    //GENERATE THE LABELS
    //if we are mapping the resultset to the datasets, do that 
    if(isset($chart->map_query) && $chart->map_query == 1){
        //echo 'generating labels';
        foreach($results as $r){
   		        if(!in_array($r->$labelColumn,$labels)){
   			        $labels[]=$r->$labelColumn;
   		        }
   	        }
    } else {
   	
        if(isset($r->$labelColumn)){
   	        foreach($results as $r){
   		        if(!in_array($r->$labelColumn,$labels)){
   			        $labels[]=$r->$labelColumn;
   		        }
   	        }
        }

    }
    //$labels = array_unique($labels);
   	$chartData["labels"] = $labels;
   //print_r($labels); exit;
    //*********************************************************


    //*********************************************************
   	//get the datasets for the chart if there are any... if not then try to build them from the configuration
   
   $chartDatasets = $this->getChartDatasets($chartId);
   $datasetData = array();
    //echo 'getting datassets'; exit;
    //it's a stacked bar chart
     //if it's a mapped query, get the datasets
     if(isset($chart->map_query) && $chart->map_query == 1){
        //echo 'mapped query'; exit;
        $chartDatasets = array();
        $colors = $this->getStackedColorsVKD();
        //for a stacked bar chart, there are multiple datasets, one for each data point for each data label group 
        //look at each data_column in each data_label_column

        //first get an array of unique values for the data_label_column
        $dataLabelArray = array();
        foreach($results as $r){
            if(!in_array($r->$chartDataLabelColumn,$dataLabelArray )){
                $dataLabelArray[] = $r->$chartDataLabelColumn;
            }           
        }

        //if there is a series column, get the values for that
        $seriesArray = array();
        if(isset($chartSeriesColumn) && $chartSeriesColumn != null && $chartSeriesColumn != ''){
            foreach($results as $r){
                if(!in_array($r->$chartSeriesColumn,$seriesArray )){
                    $seriesArray[] = $r->$chartSeriesColumn;
                }           
            }
        }

//print_r($dataLabelArray);        
//print_r($seriesArray); exit;
        //print_r($chart);
        //print_r($results);
        //echo '<br><br>';
        //foreach label
        $datasetCnt = 0;
        foreach($dataLabelArray as $d){

        //if there is a chart series column value, we have to break the labels up further into the value of those columns

            $cd = new stdClass();
            $data = array();
            //foreach result
            foreach($results as $r){
                //if the label column of the result equals the current label, then add it to the data
                if($r->$chartDataLabelColumn == $d ){
                    $data[] = $r->$chartDataColumn;
                }
            }
            $cd->label = $d ;
   		    $cd->data = $data;
            $cd->background_color = $colors[$datasetCnt];
            $cd->border_color = $colors[$datasetCnt];
            $chartDatasets[] = $cd;
            //print_r($l);
            $datasetCnt ++;


        }

   			 
        //print_r($chartDatasets);
        //exit;

     } else {

   	     foreach($chartDatasets as $cd){
   		        $data = array();
   		        foreach($datasetData as $key => $val){
   		        if(isset($val[$cd->label])){
	   		        $data[] = $val[$cd->label];
   			        } else {
   				        $data[]=null;
   			        }
   		        }
   		        $cd->data = $data;
	      }

      }
   	
    //echo 'end'; exit;	
 
   
   	$dataDatasets = array();
   	foreach($chartDatasets as $cd){
   		$dArray = array();
   		$dArray['label'] = $cd->label;
   		$dArray['data'] = $cd->data;
        if(isset($cd->data_labels)){ $dArray['dataLabels'] = $cd->data_labels; }
   		$dArray['backgroundColor'] = $cd->background_color;
   		$dArray['borderColor'] = $cd->border_color;
        if(isset($cd->border_width)){ $dArray['borderWidth'] = $cd->border_width; }
   		if(isset($cd->fill) && $cd->fill == 0){
   			$dArray['fill'] = false;
   		} else {
   			$dArray['fill'] = true;
   		}
   		$dataDatasets[] = $dArray;
   		 
   	}
    
    
   	$chartData["datasets"] = $dataDatasets;
    //print_r($chartData); exit;
   	//print_r(json_encode($chartData["datasets"])); exit;
   	return $chartData;
   
   }
      
   function getLineChart($chartId){
   	//SELECT havyc_child_population_by_age.*, name FROM havyc_child_population_by_age
   	//join havyc_age_group on havyc_age_group.id = havyc_child_population_by_age.age_group
   	//where is_current = 1;

   	$chartData = array();
   	$chartData["chart_type"] = 'line';
   	  	
   	//get the chart data
   	$chart = $this->getChart($chartId);
   	//print_r($chart); exit;
   	$chartData["chart_title"] = $chart->chart_title;
   	$chartData["chart_sub_title"] = $chart->chart_sub_title;
   	$chartData["y_data_type"] = $chart->y_data_type;
   	$chartData["x_data_type"] = $chart->x_data_type;
   	$chartData["y_min"] = $chart->y_min;
   	$chartData["y_max"] = $chart->y_max;
   	$chartData["id"] = $chart->id;
   	$chartData["show_lines"] = $chart->show_lines;
   	$chartData["show_annotation"] = $chart->show_annotation;
   	$chartData["show_datalabels"] = $chart->show_datalabels;
    $chartData["show_legend"] = $chart->show_legend;
   	$chartLabelColumn = $chart->label_column; 
    $chartSeriesColumn = $chart->label_column; // used to break up the results into multiple series/datasets
   	$chartDataColumn = $chart->data_column;
   	$labelColumn = $chart->labels;
    $chartDataLabelColumn = $chart->data_label_column;
   	//$sql = "SELECT havyc_child_population_by_age.*, name FROM havyc_child_population_by_age
   	//join havyc_age_group on havyc_age_group.name = havyc_child_population_by_age.age_group
   	//where is_current = 1 order by year, name";
   	$sql = $chart->data_query;
   	$sql = $chart->data_query;
   	if($sql == null || $sql == ''){
   		 
   		$dataset = $this->getDataset($chart->dataset);
   		$sql = 'select * from '.$dataset->data_table.' where is_current = 1 ';
   		if(isset($chart->where_clause)){
   			$where = $chart->where_clause;
   			$sql .= $where.' ';
   		}
   		if($chart->order_column != null && $chart->order_column != ''){
   			$sql .= ' order by '.$chart->order_column;
   		}
   	}
   	
   	$query = $this->db->query($sql);
   	$results = $query->result();
   	
   	//print_r($results); exit; 

    //***************************************************************** 
   	$labels = array();
   	$datasets = array();
    //GENERATE THE LABELS
    //if we are mapping the resultset to the datasets, do that 
    if(isset($chart->map_query) && $chart->map_query == 1){
        //echo 'generating labels';
        foreach($results as $r){
   		        if(!in_array($r->$labelColumn,$labels)){
   			        $labels[]=$r->$labelColumn;
   		        }
   	        }
    } else {
   	
        if(isset($r->$labelColumn)){
   	        foreach($results as $r){
   		        if(!in_array($r->$labelColumn,$labels)){
   			        $labels[]=$r->$labelColumn;
   		        }
   	        }
        }

    }
    //$labels = array_unique($labels);
   	$chartData["labels"] = $labels;
   //print_r($labels); exit;
    //*********************************************************
   	
   	//get the datasets for the chart
    $chartDatasets = array();
     if(isset($chart->map_query) && $chart->map_query == 1){
        //echo  $chartDataLabelColumn; exit;        
        $colors = $this->getColorsVKDLine();
        //first get an array of unique values for the data_label_column
        $dataLabelArray = array();
        foreach($results as $r){
            if(!in_array($r->$chartDataLabelColumn,$dataLabelArray )){
                $dataLabelArray[] = $r->$chartDataLabelColumn;
            }           
        }


           //if there is a series column, get the unique values of that series
   $seriesColumns = array();
        if(isset($chartSeriesColumn) && $chartSeriesColumn != '' && $chartSeriesColumn != null){
            //there are multiple series, so create those now
            foreach($results as $r){
                if(!in_array($r->$chartSeriesColumn,$seriesColumns)){
                    $seriesColumns[] = $r->$chartSeriesColumn;
                }
            }
            $cnt = 0;
         foreach($seriesColumns as $s){
            $cd = new stdClass();
            $cd->label = $s;
            $cd->background_color = $colors[$cnt];
   		    $cd->border_color =  $colors[$cnt];
            $cd->border_width = "1";
   		    $cd->fill = 0;
            $data = array();
            $dataLabels = array();
            
            foreach($results as $r){
                //if the value in the series column matches the current series, add the data
                if($r->$chartSeriesColumn == $s){
                    $data[] = $r->$chartDataColumn;
                    if(isset($chartDataLabelColumn) && $chartDataLabelColumn !=null && $chartDataLabelColumn !=''){
                        $dataLabels[] = $r->$chartDataLabelColumn; 
                    }
                }
                
            }
   		    
   		    $cd->data = $data;
            $cd->data_labels = $dataLabels;
   		    $cnt ++;
   		    $chartDatasets[] = $cd; 
         }



        } else {
            $datasetCnt = 0;
            foreach($dataLabelArray as $d){
                $cd = new stdClass();
                $data = array();
                //foreach result
                foreach($results as $r){
                    //if the label column of the result equals the current label, then add it to the data
                    if($r->$chartDataLabelColumn == $d ){
                        $data[] = $r->$chartDataColumn;
                    }
                }
                $cd->label = $d ;
   		        $cd->data = $data;
                $cd->background_color = $colors[$datasetCnt];
                $cd->border_color = $colors[$datasetCnt];
                $cd->fill = 0;
                $chartDatasets[] = $cd;

                //print_r($l);
                $datasetCnt ++;
            }

        }

        //print_r($seriesColumns);
        //print_r($labels);
        //print_r($chartDatasets);
        //exit;




     } else { 

   	    $chartDatasets = $this->getChartDatasets($chartId);   	
   	    foreach($results as $r){
   		    $datasetData[$r->$labelColumn][$r->$chartLabelColumn] = $r->$chartDataColumn;
   	    }   	
   	    foreach($chartDatasets as $cd){
   		    $data = array();
   		    foreach($datasetData as $key => $val){
   			    if(isset($val[$cd->label])){
   			    $data[] = $val[$cd->label];
   			    } else {
   				    $data[] = null;
   			    }
   		    }
   		    $cd->data = $data;
   	    }

   	}

   	
   	//foreach($chartDatasets as $cd){
   		//$cd->data = $datasetData[$cd->id];
   	//}
   	
   	//print_r($chartDatasets); exit;
   	
   	$dataDatasets = array();
   	foreach($chartDatasets as $cd){
   		$dArray = array();
   		$dArray['label'] = $cd->label;
   		$dArray['data'] = $cd->data;
   		$dArray['backgroundColor'] = $cd->background_color;
   		$dArray['borderColor'] = $cd->border_color;
   		if($cd->fill == 0){
   			$dArray['fill'] = false;
   		} else {
   			$dArray['fill'] = true;
   		}
   		$dataDatasets[] = $dArray;
   		
   	}
   	$chartData["datasets"] = $dataDatasets;
   	//print_r(json_encode($chartData["datasets"])); exit;
   	return $chartData;
   	
   }
   
   function getPieChart($chartId){
   	 
   	$chartData = array();
   		
   	//get the chart data
   	$chart = $this->getChart($chartId);
   
   	$chartData["chart_type"] = $chart->chart_type;
   	$chartData["id"] = $chart->id;
   
   	$chartData["chart_title"] = $chart->chart_title;
   	$chartData["chart_sub_title"] = $chart->chart_sub_title;
   	$chartData["y_data_type"] = $chart->y_data_type;
   	$chartData["x_data_type"] = $chart->x_data_type;
   	$chartData["y_min"] = $chart->y_min;
   	$chartData["y_max"] = $chart->y_max;
    $chartData["show_legend"] = $chart->show_legend;
   	$chartLabelColumn = $chart->label_column;
   	$chartDataColumn = $chart->data_column;
   	//$labelColumn = 'household_type';
   	$labelColumn = $chart->labels;
   	//$labelColumn = 'year';
   	$sql = $chart->data_query;

    //print_r($chart); exit;
   	
   	if(($sql == null || $sql == '') && $chart->chart_datasource != 'api'){
   	
   		$dataset = $this->getDataset($chart->dataset);
   		$sql = 'select * from '.$dataset->data_table.' where is_current = 1 ';
   		if(isset($chart->where_clause)){
   			$where = $chart->where_clause;
   			$sql .= $where.' ';
   		}
   		if($chart->order_column != null && $chart->order_column != ''){
   			$sql .= ' order by '.$chart->order_column;
   		}
   	}
   	 
    if($sql != null && $sql != ''){
        $query = $this->db->query($sql);   	
   	    $results = $query->result();
    } else {
        if($chart->chart_datasource == 'api'){
            $this->load->helper('rest');
            $results = json_decode(make_request($chart->api_endpoint));
        }
    }
   	
   //echo 'label column: '.$labelColumn; 
   //print_r($results); 
   	$labels = array();
   	$datasets = array();

    //*********************************************************
    //GENERATE THE LABELS
    //if we are mapping the resultset to the datasets, do that 
    if(isset($chart->map_query) && $chart->map_query == 1){
        //echo 'generating labels';
        foreach($results as $r){
   		        if(!in_array($r->$labelColumn,$labels)){
   			        $labels[]=$r->$labelColumn;
   		        }
   	        }
    } else {
   	
        if(isset($r->$labelColumn)){
   	        foreach($results as $r){
   		        if(!in_array($r->$labelColumn,$labels)){
   			        $labels[]=$r->$labelColumn;
   		        }
   	        }
        }

    }
    //*********************************************************


   	//$labels = array_unique($labels);
   //print_r($labels); exit;
   	$chartData["labels"] = $labels;
   
   //*********************************************************
    //GENERATE THE DATASETS	 
   	//get the datasets for the chart

    if(isset($chart->map_query) && $chart->map_query == 1){
        //auto generate the dataset
        $chartDatasets = array();
        //bulid the datasets from the labels
        $colors = $this->getColorsVKD();
        //Example of building one dataset from the entire result set
        $cd = new stdClass();
        $data = array();
        $dataLabels = array();
        $cnt = 0;
        foreach($results as $r){
            $data[] = $r->$chartDataColumn;
            if(isset($chartDataLabelColumn) && $chartDataLabelColumn !=null && $chartDataLabelColumn !=''){
                $dataLabels[] = $r->$chartDataLabelColumn; 
            }
            //set a different background color for each
            //$backgroundColors[] = $this->getColor($cnt);
            //$borderColors[] = $this->getBorderColor($cnt);
            $cnt ++;
        }
   		$cd->label = '';
   		$cd->data = $data;
        /*
        $cd->data_labels = $dataLabels;
   		$cd->background_color = $colors;
   		$cd->border_color = $colors;
        $cd->border_width = "1";
   		$cd->fill = 1;
        */
   		$chartDatasets[] = $cd;  

    } else {
   	$chartDatasets = $this->getChartDatasets($chartId);
	 
     //print_r($labels); 
      //print_r($chartDatasets); 
       //print_r($results); 
   	//data need to be in the same order as the labels
   	$datasetData = array();
   	foreach($labels as $l){
   		foreach($chartDatasets as $cd){
   			$tmpData = null;
   			foreach($results as $r){
   				if($r->$labelColumn == $l){
   					$tmpData = $r->$chartDataColumn;
   				}
   			}
   			$datasetData[$cd->id][] = $tmpData;
   		}
   	}
    //print_r($datasetData);
    //exit;
      foreach($chartDatasets as $cd){
   		$cd->data = $datasetData[$cd->id];
   	}

    }
     //*********************************************************


   	//print_r($chartDatasets); exit;

   	
   	//add colors to the chart
   	$bgColors = array();
   	$index = 0;
   	$colors = $this->getColorsVKD();
   	if(isset($cd->data)){
	   	foreach($cd->data as $d){
	   		$color = $this->getColor($index);
	   		$bgColors[] = $color;
	   		$index ++;
	   	}
   	}
   	 
   	//print_r($cd->data); exit;
   	 
   	$dataDatasets = array();
    //for doughnut charts - single indicators
   
   	foreach($chartDatasets as $cd){
        $dataVals = $cd->data;
   		$dArray = array();
   		$dArray['label'] = 'Dataset 1';  		
   		$backgroundColors = $colors;
   		//$dArray['borderColor'] = $cd->border_color;
        /*
   		if($cd->fill == 0){
   			$dArray['fill'] = false;
   		} else {
   			$dArray['fill'] = true;
   		}
   		
           */
        //if it's a doughnut chart, and the type is percent, add a second dataset so we can render the percentage in 
        //the circumference of the doughnut        
        if($chart->chart_type=='doughnut' && $chart->y_data_type == 'percent'){
           $dataVals[] = (100 - $cd->data[0]); 
           $backgroundColors = array();
           $backgroundColors[] = $colors[0];
           $backgroundColors[] = '#dddddd';
        }
        $dArray['data'] = $dataVals;  
        $dArray['backgroundColor'] = $backgroundColors;
        $dataDatasets[] = $dArray;
   
   	}

   	$chartData["datasets"] = $dataDatasets;
   	//print_r(json_encode($chartData["datasets"])); exit;
   // print_r($chartData); exit;


   	return $chartData;
   	 
   }
   
   function getColor($index){
   	
   	    $colors = array();
   	    $colors[0] = "rgb(54, 162, 235, .3)";
   	    $colors[1] = 'rgb(255, 99, 132, .3)';
		$colors[2] = 'rgb(255, 159, 64, .3)';
		$colors[3] = 'rgb(255, 205, 86, .3)';
		$colors[4] = 'rgb(75, 192, 192, .3)';
		$colors[5] = 'rgb(54, 162, 235, .3)';
		$colors[6] = 'rgb(153, 102, 255, .3)';
		$colors[7] = 'rgb(201, 203, 207, .3)';
		$colors[8] = 'rgb(150,95,168, .3)';
		
		return $colors[$index];
		
   }

   function getBorderColor($index){
   	
   	    $colors = array();
   	    $colors[0] = "rgb(54, 162, 235)";
   	    $colors[1] = 'rgb(255, 99, 132)';
		$colors[2] = 'rgb(255, 159, 64)';
		$colors[3] = 'rgb(255, 205, 86)';
		$colors[4] = 'rgb(75, 192, 192)';
		$colors[5] = 'rgb(54, 162, 235)';
		$colors[6] = 'rgb(153, 102, 255)';
		$colors[7] = 'rgb(201, 203, 207)';
		$colors[8] = 'rgb(150,95,168)';
		
		return $colors[$index];
		
   }

   function getColorsVKD(){
        $colors = array();
        
        $colors[] = '#007155';
        $colors[] = '#3b886e';
        $colors[] = '#60a088';
        $colors[] = '#84b8a3';
        $colors[] = '#a7d0bf';
        $colors[] = '#cae9dc';

        $colors[] = '#007155';
        $colors[] = '#3b886e';
        $colors[] = '#60a088';
        $colors[] = '#84b8a3';
        $colors[] = '#a7d0bf';
        $colors[] = '#cae9dc';
        

        $colors[] = '#e5f5e0';
        $colors[] = '#c7e9c0';
        $colors[] = '#a1d99b';
        $colors[] = '#74c476';
        $colors[] = '#41ab5d';
        $colors[] = '#238b45';
        $colors[] = '#006d2c';
        $colors[] = '#00441b';

        $colors[] = '#ed7330';
        $colors[] = '#ed8751';
        $colors[] = '#eb9a71';
        $colors[] = '#e6ad90';
        $colors[] = '#ddbfb0';
        $colors[] = '#d1d1d1';
        return $colors;
    }

   function getColorsVKDline(){
        $colors = array();
        
        $colors[] = '#007155';
        $colors[] = '#ed7330';
        $colors[] = '#feb24c';
        $colors[] = '#f03b20';


        $colors[] = '#2b8cbe';
        $colors[] = '#c51b8a';
        $colors[] = '#feb24c';



        return $colors;
    }

   function getStackedColorsVKD(){

        $colors = array();
       
        $colors[] = '#007155';
        $colors[] = '#84b8a3';
        $colors[] = '#cae9dc';

        
        $colors[] = '#007155';
        $colors[] = '#3b886e';
        $colors[] = '#60a088';
        $colors[] = '#84b8a3';
        $colors[] = '#a7d0bf';
        $colors[] = '#cae9dc';
        

        $colors[] = '#e5f5e0';
        $colors[] = '#c7e9c0';
        $colors[] = '#a1d99b';
        $colors[] = '#74c476';
        $colors[] = '#41ab5d';
        $colors[] = '#238b45';
        $colors[] = '#006d2c';
        $colors[] = '#00441b';

        $colors[] = '#ed7330';
        $colors[] = '#ed8751';
        $colors[] = '#eb9a71';
        $colors[] = '#e6ad90';
        $colors[] = '#ddbfb0';
        $colors[] = '#d1d1d1';
       
        return $colors;
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
   
   function getDatasetColors(){
   	$colors = array();
   	$colors['red'] = 'rgb(255, 99, 132)';
   	$colors['orange'] = 'rgb(255, 159, 64)';
   	$colors['yellow'] = 'rgb(255, 205, 86)';
   	$colors['green'] = 'rgb(75, 192, 192)';
   	$colors['blue'] = 'rgb(54, 162, 235)';
   	$colors['purple'] = 'rgb(150,95,168)';
   	$colors['light grey'] = 'rgb(201, 203, 207)';
   	$colors['dark grey'] = 'rgb(98, 100, 92)';
   	//BBF specific brand colors
   	$colors['bbf blue'] = '#466fb6';
   	$colors['bbf red'] = '#ee2d67';
   	$colors['bbf yellow'] = '#fdca0b';
   	$colors['bbf purple'] = '#a05cbf';
   	$colors['bbf green'] = '#6abf4b';
   	$colors['bbf orange'] = '#ff9e18';
   	$colors['bbf black'] = '#342f20';
   	$colors['bbf dark grey'] = '#65665d';
   	$colors['bbf light grey'] = '#d6d1c4';
   	return $colors;
   }
   
   function getTablesForDataset($datasetId){
   	$this->db->select('*');
   	$this->db->from('havyc_table');
   	$this->db->where('dataset',$datasetId);
   	$query = $this->db->get();
   	$results = $query->result();
   	//print_r($this->db->last_query());
   	//print_r($results); exit;
   	return $results;
   }
   
   function getTable($id){
   	$retVal = array();
   	$this->db->select('*');
   	$this->db->from('havyc_table');
   	$this->db->where('id',$id);
   	$query = $this->db->get();
   	$results = $query->result();
   	if(isset($results[0])){
   		$retVal = $results[0];
   	}
   	return $retVal;
   }
   
   function getTableData($id){
   	$tableData = array();
   	 
   	//get the chart data
   	$table = $this->getTable($id);   	  
   	$tableData["title"] = $table->title;
   	$tableData["headerBgColor"] = $table->header_bgcolor;
   	$tableData['columns'] = explode(',',$table->columns);
   	$sql = $table->data_query;
   	
   	//echo $sql;
   	 
   	$query = $this->db->query($sql);
   	$results = $query->result_array();
   	
   	$tableData['dataset'] = $results;

    //get any associated table filters if they exist
    $tableFilters = $this->getTableFilters($id);
    //get filter options
    $filters = array();
    foreach($tableFilters as $f){
        $tmpFilter = array();
        $tmpFilter["label"] = $f->filter_label;
        //get the options
        $options = $this->getTableFilterOptions($f->filter_query);
        $tmpFilter["options"] = $options;
        $filters[] = $tmpFilter;
    }
    //print_r($filters); exit;
    $tableData['filters'] = $filters;
   	
   	return $tableData;
   	
   }
   
   function getTableFilters($tableId){
   	    $this->db->select('*');
   	    $this->db->from('havyc_table_filter');
   	    $this->db->where('table_id',$tableId);
   	    $query = $this->db->get();
   	    $results = $query->result();
   	    return $results;
    }

   function getTableFilterOptions($sql){
        $query = $this->db->query($sql);
   	    $results = $query->result_array();
        return $results;
    }
   
   
}