<?php session_start(); error_reporting( E_ERROR );?>
<?php
	include ('db.php');
	
	//создание подготавливаемого запроса
	$stmt = $mysqli->prepare
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
	
	//связывание параметров с метками
	//$id = 1;
	//$stmt->bind_param("i", $id);

	//выполнение запроса
	$stmt->execute();

	//связывание переменных с результатами запроса
	$stmt->bind_result($res1, $res2);

	/* Выбрать значения */
    while ($stmt->fetch()) {
		printf ("%s|%s;",$res1, $res2);
	}
?>
