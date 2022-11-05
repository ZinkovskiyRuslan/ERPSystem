<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('manager'),
		"
			UPDATE
					`devices`
			SET
					`Device` = ?
			WHERE 
					`devices`.`Id` = ?
		",
		array('si', $_POST["device"], $_POST["id"]), 
		true
	);
?>