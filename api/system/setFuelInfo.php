<?php
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		"
			UPDATE
					`fuelinformations`
			SET
					FuelFill = IFNULL(FuelFill, 0) + ?
			WHERE
					`fuelinformations`.`Id` =
					(
						SELECT
								Min(Id)
						FROM
								`fuelinformations`
						WHERE
								`fuelinformations`.`UserId` =
									(
										SELECT
												id 
										FROM
												`users`
										 WHERE
												`users`.`DeviceId` = ?
									)	AND
									Fuel > IFNULL(FuelFill, 0)
					)
		",
		array('si', $_GET['id'], $_GET['fuel']),
		true
	);
	
	/*
	$importDbActions = array("setFuelInfo");
	include_once('../../db.php');
	echo(setFuelInfo($db, $_GET['id'], $_GET['fuel']));
	
	function setFuelInfo($db, $id, $fuel)
	{
		//создание подготавливаемого запроса
		$stmt = $db->prepare
		("
			UPDATE
					`fuelinformations`
			SET
					FuelFill = IFNULL(FuelFill, 0) + ?
			WHERE
					`fuelinformations`.`Id` =
					(
						SELECT
								Min(Id)
						FROM
								`fuelinformations` 
						WHERE
								`fuelinformations`.`UserId` =
									(
										SELECT 
												id 
										  FROM 
												`users` 
										 WHERE 
												`users`.`DeviceId` = ?
									)	AND
									Fuel > IFNULL(FuelFill, 0)
					)
		");

		//связывание параметров с метками
		$stmt->bind_param("si", $fuel, $id);

		// выполняем запрос
		if ($stmt->execute()) {
			return "Ok";
		}
		return "Error update fuelinformations";
	}
	*/
?>
