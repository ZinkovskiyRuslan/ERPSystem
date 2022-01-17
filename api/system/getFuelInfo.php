<?php
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	$result = ExecSQL(
		"
			SELECT 
			GROUP_CONCAT(CONCAT(`DeviceId`, '|', `Fuel` - `FuelFill`) ORDER BY fi.`Id` Desc SEPARATOR ';')  as result
			FROM
					`fuelinformations` fi,
					`users` u
			 WHERE
					fi.UserId = u.Id	AND
					fi.Fuel > IFNULL(fi.FuelFill,0) 
		",
		array(), 
		false
	);
	echo($result[0]["result"]);
	/*
	$importDbActions = array("getFuelInfo");
	include_once('../../db.php');
	echo(getFuelInfo($db));
	
	function getFuelInfo($db)
	{
		$result = "";

		//создание подготавливаемого запроса
		$stmt = $db->prepare
		("
			SELECT
					u.DeviceId,
					fi.Fuel - IFNULL(fi.FuelFill,0)
			  FROM
					`fuelinformations` fi,
					`users` u
			 WHERE
					fi.UserId = u.Id	AND
					fi.Fuel > IFNULL(fi.FuelFill,0) 
		");

		//выполнение запроса
		$stmt->execute();

		//связывание переменных с результатами запроса
		$stmt->bind_result($res1, $res2);
		while ($stmt->fetch()) {
			$result = $result . $res1 . "|" . $res2 . ";";
		}

		return $result;
	}
	*/
?>
