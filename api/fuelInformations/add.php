<?php
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		"
			INSERT INTO 
				`fuelinformations`
				(
					`UserId`,
					`CarId`,
					`Fuel`
				)
			VALUES
			(
				?,
				?,
				?
			);
		",
		array('iii', $_POST["userId"], $_POST["carId"], $_POST["fuel"]),
		true
	);
?>