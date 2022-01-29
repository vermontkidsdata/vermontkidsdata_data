<?php

/*
 * SET SQL_SAFE_UPDATES = 0;
update havyc.d_vdh_ahs_population_estimates p
set bbf_region = (select bbf_region from havyc.gaz_geo_map m where m.ahs_district = p.ahs_district limit 1)
 */

// Connect to server and select databse.
$conn = new mysqli('localhost','root','','havyc');
if ($conn->connect_error) {
	die('Error : ('. $conn->connect_errno .') '. $conn->connect_error);
}

$importFile = "C:/tmp/bbf/vdh_2018f.csv";
//$importFile = $argv[1];
$year = '2018';
$gender = 'female';

	$f = fopen($importFile, 'r');
	//echo $fp; break;
	$pos = 0;
	while ( !feof($f) )
	{
		$line = fgets($f, 2048);
		$delimiter =  ",";
		$data = str_getcsv($line, $delimiter);
		//print_r($data); exit;
		if($pos == 0){
			$headers = $data;
		} else {
			if(count($data)>1){
				print_r($headers);
				echo '<br>';
				print_r($data);
				echo '<br>';
				$age = $data[0];
				foreach($data as $key=>$val){
					if($key != 0){
						$sql = "insert into d_vdh_ahs_population_estimates (year,age,gender,ahs_district,population) 
								values (".$year.",".str_replace('+','',$age).",'".$gender."','".$headers[$key]."',".$val.")";
						echo $sql;
						echo '<br>';
						if ($conn->query($sql) === TRUE) {
							echo "New record created successfully";
							echo '<br>';
						} else {
							echo "Error: " . $sql . "<br>" . $conn->error;
							echo '<br>';
						}
					}
				}
			}
			//exit;
		}
		
		
		$pos ++;
	}
		
	fclose($f);
	
	$conn->close();
			
?>