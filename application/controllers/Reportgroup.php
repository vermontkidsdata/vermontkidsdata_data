<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportgroup extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
		// Force SSL
		//$this->force_ssl();
	
		// Form and URL helpers always loaded (just for convenience)
		$this->load->helper('url');
		$this->load->helper('form');
	}

	/**
	 * Controller for managing ACS report groups.
	 */
	public function index()
	{
		//if( $this->require_role('admin') )
		//{
			$this->load->model('Reportgroup_model');
			$reports = $this->Reportgroup_model->getReportGroupReports();
			$data['reports'] = $reports;
			$this->load->view('admin/reportgroup/index', $data);
		//}
	}
	
	public function edit($reportId = 1){
		
		$this->load->model('Report_model');
		$report = $this->Report_model->getReport($reportId);
		$data['report'] = $report;
		
		$this->load->model('Reportgroup_model');
		$reportGroups = $this->Reportgroup_model->getReportGroups($reportId);
		$data['reportGroups'] = $reportGroups;
		
		$templates = $this->Report_model->getReportTemplates();
		$data['templates'] = $templates;
		
		$this->load->view('admin/reportgroup/edit',$data);
		
	}
	
	function generate_defaults(){
		//function to generate stub report groups for an ACS report
		print_r($_POST);
		$reportId = $_POST['report_id'];
		$variableGroupsInput = $_POST['variable_groups'];
		$ageCategoriesInput = $_POST['age_categories'];
		$additionalColumnsInput = $_POST['additional_columns'];
		$template = $_POST['template'];
		$numerator = $_POST['numerator_name'];
		$denominator = $_POST['denominator_name'];
		$genders = array();
		$dimensionName = '';
		
		$variableGroups = explode(",",$variableGroupsInput);
		$variableGroupsCnt = count($variableGroups);
		
		$ageCategories = explode(",",$ageCategoriesInput);
		$ageCategoriesCnt = count($ageCategories);
		
		$additionalColumns = explode(",",$additionalColumnsInput);
		$additionalColumnsCnt = count($additionalColumns);
		$totalColumns = array();
		$totalColumns[] = $numerator;
		$totalColumns[] = $denominator;
			foreach($additionalColumns as $ac){
				if($ac != null && $ac != "" && (strlen($ac) > 0)){
					$totalColumns[] = $ac;
				}
			}

		//print_r($additionalColumns); exit;
		//print_r($totalColumns); exit;
		
		$totalColumnsCnt = count($totalColumns);
		
		if(isset($_POST['genders1'])){
			$genders[] = 'Male';
		}
		if(isset($_POST['genders2'])){
			$genders[] = 'Female';
		}
		if(isset($_POST['genders3'])){
			$genders[] = 'All';
		}
		echo '<br><br>';
		$groupId = 1;
		$rowCount = 1;
		//foreach variable groups
		$this->load->model('Report_model');
		$this->load->model('Reportgroup_model');
		
		print_r($variableGroupsCnt);
		echo '<br><br>';
		print_r($variableGroups);
		echo '<br><br>';
		print_r($totalColumns);
		echo '<br><br>';
		for($vg = 0; $vg < $variableGroupsCnt; $vg ++){
			
			$variableGroup = $variableGroups[$vg];
			$dimensionNames = $this->Report_model->getDimensionsForReport($reportId);
			//$dimensionNames = $this->Report_model->getDimensionNames($variableGroup);
			print_r($dimensionNames); 
			//exit;
		//foreach ages
			for($ac = 0; $ac < $ageCategoriesCnt; $ac ++){
				$ageCategory = $ageCategories[$ac];
				//foreach gender
				for($g = 0; $g < count($genders); $g ++){
					$gender = $genders[$g];
					
				//foreach dimension
				for($dc = 0; $dc < count($dimensionNames); $dc ++){
					$dimensionName = $dimensionNames[$dc];
				//foreach data point
					for($tc = 0; $tc < $totalColumnsCnt; $tc ++){
						$column = $totalColumns[$tc];
						//echo $rowCount.' '.$groupId.'<br>';
						//variables for defaults
						$isNumerator = 0;
						$isDenominator = 0;
						$isValue = 0;
						$isTotal = 0;
						
						$iData = array();
						$iData['report_id'] = $reportId; 	
						$iData['report_group_id'] = $groupId;
						$iData['table_variables'] = $variableGroup;
						$groupName = "Estimate!!";
											
						if($tc == 0){
							//numerator
							$groupName .= $numerator;
							$isNumerator = 1;
							$isDenominator = 0;
							$isValue = 1;
							$isTotal = 0;
						} else if($tc == 1){
							//denominator
							$groupName .= $denominator;
							$isNumerator = 0;
							$isDenominator = 1;
							$isValue = 0;
							$isTotal = 1;
						} else {
							$groupName .= $column;
						}
						
						$groupName .= "!!$gender!!$ageCategory";
						$groupName .= $dimensionName;
						
						
						$iData['report_group_name'] = $groupName;
						$iData['is_numerator'] = $isNumerator;
						$iData['is_denominator'] = $isDenominator;
						$iData['is_value'] = $isValue;
						$iData['is_total'] = $isTotal;
						
							print_r($iData);
							$this->Reportgroup_model->createReportGroup($iData);
							print_r($iData);
							echo '<br><br>';
							$rowCount++	;

				
					}
					$groupId ++;
				}
		
				}
			
			}
			
		}
		//exit;
		redirect(base_url().'reportgroup/edit/'.$reportId);
		
	}
	
	function ajax_save_variables(){
		
		$this->load->model('Reportgroup_model');
		$id = $_POST['id_acs_report_group'];
		$data['table_variables'] = $_POST['table_variables'];
		$data['report_group_name'] = $_POST['report_group_name'];
		$this->Reportgroup_model->updateReportGroup($id,$data);
		$this->output->set_output($id);
	}
	
}
