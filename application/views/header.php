<!DOCTYPE html>

<html lang="en">

	<head>
	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	
	<meta http-equiv='cache-control' content='no-cache'> 
	<meta http-equiv='expires' content='0'> 
	<meta http-equiv='pragma' content='no-cache'> 

    <!-- Fontawesome -->
	<script src="https://kit.fontawesome.com/4b998386c8.js" crossorigin="anonymous"></script>

    <!-- CoreUI CSS -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/@coreui/coreui/dist/css/coreui.min.css" crossorigin="anonymous">  
    <link rel="stylesheet" href="https://unpkg.com/@coreui/icons@2.0.0-beta.3/css/all.min.css">
    -->
	<link rel="stylesheet" href="/stylesheets/coreui/coreui.min.css" crossorigin="anonymous">
	<link rel="stylesheet" href="/stylesheets/coreui/all.min.css">

	<link rel="stylesheet" href="/stylesheets/bbf.css">

	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
 	
 	<!-- CoreUI CSS -->
 	<!-- <link rel="stylesheet" href="https://unpkg.com/@coreui/coreui/dist/css/coreui.min.css" crossorigin="anonymous">  -->
 	<link rel="stylesheet" href="/stylesheets/coreui/coreui.min.css" crossorigin="anonymous">
 	
 	<link rel="stylesheet" href="https://unpkg.com/@coreui/icons@2.0.0-beta.3/css/free.min.css">
	<link rel="stylesheet" href="https://unpkg.com/@coreui/icons@2.0.0-beta.3/css/brand.min.css">
	<link rel="stylesheet" href="https://unpkg.com/@coreui/icons@2.0.0-beta.3/css/flag.min.css">
	
	<script src="/scripts/bbf.js?v=<?php echo time(); ?>"></script> 
	<script src="/scripts/census/census.js?v=<?php echo time(); ?>"></script> 
 	
 	<script src="/scripts/Chart.js"></script> 
 	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"></script>-->
 	<script src="/scripts/chartjs-plugin-annotation.js"></script>
 	<script src="/scripts/chartjs-plugin-datalabels.js"></script>
	<link rel="stylesheet" href="/stylesheets/Chart.css">
 		
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
			
	<script src="/scripts/jquery-1.7.1.min.js"></script>
	<script src="/scripts/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/stylesheets/smoothness/jquery-ui-1.9.2.custom.min.css">
	<link rel="stylesheet" type="text/css" href="/scripts/datatables/css/datatables.css"> 
	<script type="text/javascript" charset="utf8" src="/scripts/datatables/js/datatables.js"></script>
	
	<script type="text/javascript">
	<?php 
	//set the base for the data api
	
	if($_SERVER['SERVER_ADDR'] == '127.0.0.1'){
		echo 'var baseDataURL = "http://bbf:8080";';
		//echo 'var baseDataURL = "https://data.vermontkidsdata.org";';
	} else {
		echo 'var baseDataURL = "https://data.vermontkidsdata.org";';
	}
	?>
	</script>

		
	<title>BBF</title>


	</head>

	<body style="margin: 0px;" class="c-app">

