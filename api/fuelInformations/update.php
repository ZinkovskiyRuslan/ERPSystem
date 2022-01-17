<?php
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		"
			UPDATE
					`fuelinformations`
			SET
					`UserId` = ?,
					`CarId` = ?,
					`Fuel` = ?
			WHERE 
					`fuelinformations`.`Id` = ?
		",
		array('iiii', $_POST["userId"], $_POST["carId"], $_POST["fuel"], $_POST["id"]), 
		true
	);
?>