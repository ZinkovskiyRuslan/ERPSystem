<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('admin'),
		"
			UPDATE
					`cars`
			SET
					`Removed` = '1'
			WHERE 
					`cars`.`Id` = ?
		",
		array('i', $_POST["id"]),
		true
	);
?>