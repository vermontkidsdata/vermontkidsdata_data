<?php 

function make_request($URL){

//$URL = 'http://vb3.civicore.com/?api={"key":"67a6f27eb5609cf20e0c52e02d2607bd.3.adf2d2ed4dc57991dcc7d6b7ae45a41a.1438791911","function":"getAll","tableName":"providers"}';
	 
$ch = curl_init($URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$output = curl_exec($ch);
curl_close($ch);

//print_r($output); exit;

return $output;

}

function clean_xml($xml){
	
	libxml_use_internal_errors(true);
	$dom = new DOMDocument("1.0", "UTF-8");
	$dom->strictErrorChecking = false;
	$dom->validateOnParse = false;
	$dom->recover = true;
	$dom->loadXML($xml);
	$xml2 = simplexml_import_dom($dom);
	
	libxml_clear_errors();
	libxml_use_internal_errors(false);
	
	return $xml2;
}

?>