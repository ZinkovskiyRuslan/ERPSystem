<?php
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$db = new mysqli("localhost", "046531842_ruslan", "+M4FVuUsS7RN", "9271031610_ruslan");
	$db->set_charset("utf8");
	
	foreach($importDbActions as $importDbAction)
	{
		switch ($importDbAction) {
			case "login":
				include_once('dbActions/login.php');
				break;
			case "getFuelInfo":
				include_once("dbActions/getFuelInfo.php");
				break;
			case "setFuelInfo":
				include_once("dbActions/setFuelInfo.php");
				break;
			case "getFuelInformations":
				include_once("dbActions/getFuelInformations.php");
				break;
			default:
				echo "</br>Unsupported method: " . $importDbAction;
		}
	}
	
	function execSQL($sql, $params, $close){
           $mysqli = new mysqli("localhost", "046531842_ruslan", "+M4FVuUsS7RN", "9271031610_ruslan");
           
           $stmt = $mysqli->prepare($sql) or die ("Failed to prepared the statement!");
          
           call_user_func_array(array($stmt, 'bind_param'), refValues($params));
          
           $stmt->execute();
          
           if($close){
               $result = $mysqli->affected_rows;
           } else {
               $meta = $stmt->result_metadata();
           
               while ( $field = $meta->fetch_field() ) {
                   $parameters[] = &$row[$field->name];
               } 
       
            call_user_func_array(array($stmt, 'bind_result'), refValues($parameters));
              
            while ( $stmt->fetch() ) { 
               $x = array(); 
               foreach( $row as $key => $val ) { 
                  $x[$key] = $val; 
               } 
               $results[] = $x; 
            }

            $result = $results;
           }
          
           $stmt->close();
           $mysqli->close();
          
           return  $result;
   }
  
    function refValues($arr){
		/*
        if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
        {
            $refs = array();
            foreach($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }*/
        return $arr;
    }
?>
