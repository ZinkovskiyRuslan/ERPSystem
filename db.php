<?php
	function execSQL($sql, $params, $close){
           $mysqli = new mysqli("", "", "", "");
           $mysqli->set_charset("utf8");
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

	function ok($result){
		http_response_code(200);
		echo json_encode($result);
	}
	
	function jsonExecSQL($sql, $params, $close){
		ok(execSQL($sql, $params, $close));
	}
	
	function jsonFirsrOrDefaultExecSQL($sql, $params, $close){
		ok(execSQL($sql, $params, $close)[0]);
	}
?>
