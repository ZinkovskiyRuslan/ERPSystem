<?php session_start();
	//Example request http://erpelement.ru/api/fuelInformations/get.php"	
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	
	$zeroHour = mktime(18, 0, 0, date("m"), date("d")-1, date("Y"));
	$zeroHourSql = date("Y-m-d H:i:s",$zeroHour);
	
	/*ToDo Вынести в крон*/
	//Блокировать заправки старше 1 суток
	ExecSQL(
		array('manager'),
		"
			UPDATE
					`fuelinformations`
			SET
					`Closed` = '1'
			WHERE 
					`fuelinformations`.`CreationDate` < ?
		",
		array('s', $zeroHourSql),
		true
	);
	
	
	jsonExecSQL(
		array('manager'),
		"
			SELECT
					fi.Id,
					fi.UserId,
					fi.CarId,
					u.FirstName,
					u.MiddleName,
					u.LastName,
					CASE 
						WHEN fi.IsResident THEN CONCAT(u.LastName, ' ', u.FirstName, ' ', u.MiddleName)
						ELSE fi.Driver 
					END as FIO,
					CASE 
						WHEN fi.IsResident THEN c.Number 
						ELSE fi.Number 
					END as CarNumber,
					fi.Fuel,
					fi.FuelFill,
					fi.Blocked,
					fi.FuelFillDate,
					fi.FillByButton,
					fi.Closed,
					fi.FillType,
					fi.IsResident,
					fi.Driver,
					fi.Number
			FROM
					`fuelinformations` fi LEFT JOIN `cars` c ON fi.CarId = c.Id
					LEFT JOIN `users` u ON fi.UserId = u.Id
			WHERE
					fi.Removed = 0 AND
					fi.CreationDate >= ? AND
					fi.CreationDate < DATE_ADD(?, INTERVAL + 1 DAY )
			Order By
					fi.Id Desc
		",
		array('ss',$_POST["dateBegin"],$_POST["dateEnd"]), 
		false
	);
?>