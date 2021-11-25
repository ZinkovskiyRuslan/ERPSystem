<?php session_start(); error_reporting( E_ERROR );?>
<?php
	include ('db.php');
	
	if(isset($_GET['id']) and isset($_GET['fuel']) ) {
		$id = $_GET['id'];
		$fuel = $_GET['fuel'];
	}
	
	//создание подготавливаемого запроса
	$stmt = $mysqli->prepare
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

	//выполнение запроса
	$stmt->execute();

	//связывание переменных с результатами запроса
	$stmt->bind_result($res1, $res2);

	/* Выбрать значения */
    while ($stmt->fetch()) {
		printf ("%s|%s;",$res1, $res2);
	}
?>
