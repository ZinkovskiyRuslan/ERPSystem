<?php
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		"
			SELECT
					fi.Id,
					fi.UserId,
					fi.CarId,
					u.FirstName,
					u.MiddleName,
					u.LastName,
					CONCAT(u.LastName, ' ', u.FirstName, ' ', u.MiddleName) as FIO,
					u.DeviceId,
					c.Number,
					fi.Fuel,
					fi.FuelFill,
					fi.Blocked,
					fi.FuelFillDate
			FROM
					`fuelinformations` fi LEFT JOIN `cars` c ON fi.CarId = c.Id,
					`users` u
			WHERE
					fi.Removed = 0 AND
					fi.UserId = u.Id
			Order By
					fi.Id Desc
			LIMIT 100
		",
		array(), 
		false
	);
?>