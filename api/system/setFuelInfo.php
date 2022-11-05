<?php
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('anonymous'),
		"
			UPDATE
					`fuelinformations`
			SET
					FuelFill = IFNULL(FuelFill, 0) + ?,
					FuelFillDate = current_timestamp(),
					Closed = 1
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
												u.id 
										FROM
												`users` u LEFT JOIN  `devices` d ON (u.`DeviceId` = d.Id)
										 WHERE
												d.`Device` = ?
									)	AND
								Fuel > IFNULL(FuelFill, 0) AND
								Closed = 0
					)
		",
		array('is', $_GET['fuel'], $_GET['id']),
		true
	);
?>
