<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	
	//проверка на дубли при добавлении заправки по приложению
	$resultId = execSQL(
		array('anonymous'),
		"
			SELECT 
					COUNT(*) as Cnt
			  FROM 
					`fuelinformations` fi
			 WHERE 
					fi.FillType =  0 	AND
					fi.Closed 	=  0	AND
					fi.Removed 	=  0	AND
					fi.UserId	=  ?
		",
		array('i', $_POST["userId"]),
		false
	);
	
	//Добавить заправку в АЗС
	if($resultId[1][0]['Cnt'] > 0 && $_POST["fillType"] == 0)
	{
		echo(-2);
	} else {
		if($_POST["fillType"] == 2)
		{
			$_POST["fuel"] = $_POST["fuelFill"];
		}
		jsonExecSQL(
			array('manager'),
			"
				INSERT INTO 
					`fuelinformations`
					(
						`UserId`,
						`CarId`,
						`Fuel`,
						`FuelFill`,
						`FillType`
					)
				VALUES
				(
					?,
					?,
					?,
					?,
					?
				);
			",
			array('iiiii', $_POST["userId"], $_POST["carId"], $_POST["fuel"], $_POST["fuelFill"], $_POST["fillType"]),
			true
		);
	}
?>