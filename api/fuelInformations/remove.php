<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('manager'),
		"
			UPDATE
					`fuelinformations`
			SET
					`Removed` = '1'
			WHERE 
					`fuelinformations`.`Id` = ?
		",
		array('i', $_POST["id"]),
		true
	);
?>