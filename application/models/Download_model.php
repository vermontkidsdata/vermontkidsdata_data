<?php
class Download_model extends CI_Model {

  public function __construct()
   {
      $this->load->database();
   }
   
  function download($filename, $contentType, $reportName){

    $this->config->load("queries");
    $queries = $this->config->item('queries');
    if (!isset($queries[$reportName])) {
      echo "Unknown query";
      return array();
    }

    $query = $queries[$reportName];

    // See if query is limited. If not, we'll need to loop through the results
    // since unbuffered_row() doesn't seem to avoid the running out of memory
    // problem.
//    $q = $this->db->query($query);
	$mysqli  = new mysqli($this->db->hostname, $this->db->username, $this->db->password, $this->db->database);
	$uresult = $mysqli->query($query, MYSQLI_USE_RESULT);
    $rowIndex = 0;
	if ($uresult) {
	   while ($row = $uresult->fetch_assoc()) {
	   	   $this->processRow($row, $rowIndex, $filename, $contentType);
           $rowIndex++;
	   }
	   $uresult->close();
	}

//    return $this->resultsToCSV($q, true);
  }

  function processRow($row, $rowIndex, $filename, $contentType) {
    $keys = array();
    $output = "";
    $header = "";
    foreach ($row as $key => $value) {
      $keys[] = (object)array(
          "key" => $key,
          "name" => $this->baseName($key),
          "index" => count($keys),
          "type" => $this->columnType($key)
      );
    }

    // Now go thru all the keys for the row for the header
    if ($rowIndex === 0) {
        for ($idx = 0; $idx < count($keys); $idx++) {
          $header .= '"'.$keys[$idx]->name.'"';
          if ($idx < count($keys) - 1) $header .= ',';
        }

        header("Content-Type: $contentType");
        header('Content-disposition: attachment; filename="' . $filename . '"');
        
        echo $header."\n";
    }

    for ($idx = 0; $idx < count($keys); $idx++) {
        $info = $keys[$idx];
        $output .= $this->columnValueAsType($info, $row[$info->key], $idx === count($keys) - 1);
    }

    echo $output."\n";
  }
  
  function baseName($key) {
    $dpos = strpos($key, "$");
    if ($dpos === FALSE) return $key;
    else return substr($key, 0, $dpos);
  }

  function columnType($key) {
    $name = $this->baseName($key);
    if (strlen($name) === strlen($key)) return "string";

    // Assume what's after is the type to convert to
    $type = substr($key, strlen($name)+1);
    return $type;
  }

  function columnValueAsType($info, $value, $isLast) {
    $out = null;
    switch ($info->type) {
      case 'int':
        $out = (int)$value;
        break;
      case 'string':
        $out = '"'.$value.'"';
        break;
      case 'float':
        $out = (float)$value;
        break;
      default:
        echo "Unhandled field type $type!!!";
        return "";
    }

    return $out . ($isLast ? "" : ",");
  }

  // http://localhost/census.gov/admin/download/view/b17001_5_year_poverty_age_sex
  // http://localhost/census.gov/admin/download/view/b17001a_g_5_year_poverty_age_sex_race
  function resultsToCSV($q, $doOutput) {
    // Go thru the result
    $keys = array();
    $rowIndex = 0;
    $outputs = array();
    $header = "";

    while ($row = $q->unbuffered_row()) {
      $row = (array)$row;

      $output = "";

      // Record all the keys if necessary. Hopefully this is only necessary on the first row.
      if ($rowIndex === 0) {
        foreach ($row as $key => $value) {
          $keys[] = (object)array(
              "key" => $key,
              "name" => $this->baseName($key),
              "index" => count($keys),
              "type" => $this->columnType($key)
          );
        }

        // echo "keys: <pre>"; print_r($keys); echo "</pre>";

        // Now go thru all the keys for the row for the header
        for ($idx = 0; $idx < count($keys); $idx++) {
          $header .= '"'.$keys[$idx]->name.'"';
          if ($idx < count($keys) - 1) $header .= ',';
        }

        if ($doOutput) echo $header."\n";
      }

      // Now go thru all the keys for the row for the row itself
      for ($idx = 0; $idx < count($keys); $idx++) {
        $info = $keys[$idx];
        $output .= $this->columnValueAsType($info, $row[$info->key], $idx === count($keys) - 1);
      }

      if ($doOutput) {
        echo $output."\n";
      } else {
        $outputs[] = $output;
      }

      $rowIndex++;
    }

    return array($header, $outputs);
  }
}