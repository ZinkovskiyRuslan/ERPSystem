<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('admin'),
		"
			UPDATE
					`devices`
			SET
					`Removed` = '1'
			WHERE 
					`devices`.`Id` = ?
		",
		array('i', $_POST["id"]),
		true
	);
?>