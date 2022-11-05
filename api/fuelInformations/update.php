<?php session_start();
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	if($_POST["fillType"] == 2)
	{
		$_POST["fuel"] = $_POST["fuelFill"];
	}
	if(isset($_POST["isResident"]))
	{
		if($_POST["isResident"] == "true")
		{
			jsonExecSQL(
				array('manager'),
				"
					UPDATE
							`fuelinformations`
					SET
							`UserId` = ?,
							`CarId` = ?,
							`Fuel` = ?,
							`FuelFill` = ?,
							`FuelFillDate` = ?,
							`IsResident` = 1
					WHERE 
							`fuelinformations`.`Id` = ?
				",
				array('iiiisi', $_POST["userId"], $_POST["carId"], $_POST["fuel"], $_POST["fuelFill"], $_POST["fuelFillDateSql"], $_POST["id"]), 
				true
			);
		}else{
				jsonExecSQL(
				array('manager'),
				"
					UPDATE
							`fuelinformations`
					SET
							`Driver` = ?,
							`Number` = ?,
							`Fuel` = ?,
							`FuelFill` = ?,
							`FuelFillDate` = ?,
							`IsResident` = 0
					WHERE 
							`fuelinformations`.`Id` = ?
				",
				array('ssiisi', $_POST["driver"], $_POST["number"], $_POST["fuel"], $_POST["fuelFill"], $_POST["fuelFillDateSql"], $_POST["id"]), 
				true
			);
		}
	}
?>