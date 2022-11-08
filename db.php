<?php 
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	include_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
	
	function execPreparedSQL($sql, $params, $close){
		$mysqli = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);
		$mysqli->set_charset("utf8");
		$stmt = $mysqli->prepare($sql) or array(false, 500);
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

		return array(true, $result);
	}
	
	function execSQL($authorize, $sql, $params, $close){
		if(isAuthorize($authorize)){
		
			$result = execPreparedSQL($sql, $params, $close);
			 addLogSQL($sql, $params, $result);
			return $result;
		}
		$result = array(false, 403);
		 ddLogSQL($sql, $params, $result);
		return $result;
	}
	
	function addLogSQL($sqlLog, $params, $result){
		$sql = "INSERT INTO `log` 
					(
						`Id`, 
						`Sql`, 
						`Params`, 
						`Result`, 
						`CreationDate`
					) 
						VALUES 
					(
						NULL,
						?,
						?,
						?,
						current_timestamp()
					) 
				";
		execPreparedSQL($sql, array('sss', $sqlLog, json_encode($params), json_encode($result)), true);
	}
  
    function refValues($arr){
        if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
        {
            $refs = array();
            foreach($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr;
    }
	
	function isAuthorize($authorize){
		if(!is_array($authorize))
			return false;
		if(
			in_array('anonymous', $authorize) ||
			in_array($_SESSION['role'], $authorize) ||
			$_SESSION['role'] == 'admin'
		)
			return true;
		return false;
	}
	
	function ok($result){
		http_response_code(200);
		echo json_encode($result);
	}
	
	function error($code){
		http_response_code($code);
	}
	
	function jsonExecSQL($authorize, $sql, $params, $close){
		$result = execSQL($authorize, $sql, $params, $close);
		if($result[0])
			ok($result[1]);
		else
			error($result[1]);
	}
	
	function jsonFirsrOrDefaultExecSQL($authorize, $sql, $params, $close){
		$result = execSQL($authorize, $sql, $params, $close);
		if($result[0])
			ok($result[1][0]);
		else
			error($result[1]);
	}
?>
