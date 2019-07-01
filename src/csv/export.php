<?php

function convertToExcelCSV($data) {
  
  if(!is_array($data)) { return ''; }

  $csv = '';

  foreach($data as $record) {
    
    if(is_array($record)) {
      
      $csv .= convertArrayToCSV($record) . "rn";

    }

  }

  return $csv;

}

function convertArrayToCSV($record) {
  
  if(!is_array($record)) { return ''; }

  $ret = '';

  foreach($record as $field) {

    if (is_array($field)) {
      
      $ret .= convertArrayToCSV($field);

    } else {
      
      if (strpos($field,'"')!==false || strpos($field,',')!==false) {
        
        $ret .= '"' . str_replace('"','""',$field) . '"';

      }
      else {

        $ret .= $field;

      }

    }
    
    $ret .= ',';
  }
  
  return substr($ret, 0, strlen($ret)-1);
}
 
?>