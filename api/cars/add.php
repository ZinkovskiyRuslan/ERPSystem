<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('admin'),
		"
			INSERT INTO 
				`cars`
				(
					`Number`
				)
			VALUES
			(
				?
			);
		",
		array('s', $_POST["number"]),
		true
	);
?>