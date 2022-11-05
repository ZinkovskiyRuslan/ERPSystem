<?php
	//Example request http://erpelement.ru/api/system/getFuelInfo.php?systemId=0&id=Start"
	
	include_once($_SERVER['DOCUMENT_ROOT'].'/db.php');
	$zeroHour = mktime(18, 0, 0, date("m"), date("d"), date("Y"));
	if($zeroHour - time() < 0)
	{
		$zeroHour = mktime(18, 0, 0, date("m"), date("d") + 1, date("Y"));
	}
	echo($zeroHour - time() .";");	
	
	$result = execSQL(
		array('anonymous'),
		"
			SELECT 
			GROUP_CONCAT(CONCAT(d.`Device`, '|', `Fuel` - `FuelFill`) ORDER BY fi.`Id` Desc SEPARATOR ';') as result
			FROM
					`fuelinformations` fi,
					`users` u,
					`devices` d
			 WHERE
					fi.Removed	= 0		AND
					fi.Closed	= 0		AND
					fi.UserId	= u.Id	AND
					fi.Fuel 	> IFNULL(fi.FuelFill,0) and 
					u.DeviceId = d.Id
		",
		array(),
		false
	);
	echo($result[1][0]["result"].";");
		echo("1");
	
	//Ищем подключившееся устройство в бд
	$resultId = execSQL(
		array('anonymous'),
		"
			SELECT 
					d.`Id` 
			FROM 
					`devices` d 
			WHERE  
					d.`Device` = ?
			LIMIT 1;
		",
		array('s', $_GET['id']),
		false
	);
	
	//Добавить устройство в список если оно новое
	if(!isset($resultId[1][0]['Id']) && isset($_GET['id']))
	{
		execSQL(
			array('anonymous'),
			"
				INSERT INTO `devices` 
						(`Device`) 
				VALUES
						(?)
			",
			array('s', $_GET['id']),
			false
		);
	}
	
	//Блокируем изменение записи т.к. данные ушли в АЗС
	execSQL(
		array('anonymous'),
		"
			UPDATE 
					`fuelinformations` 
			   SET
					Blocked = 1
			 WHERE
					Blocked = 0
		",
		array(),
		false
	);
?>
