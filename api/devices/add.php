<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('admin'),
		"
			INSERT INTO 
				`devices`
				(
					`Device`
				)
			VALUES
			(
				?
			);
		",
		array('s', $_POST["device"]),
		true
	);
?>