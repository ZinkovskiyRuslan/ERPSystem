<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('manager'),
		"
			UPDATE
					`cars`
			SET
					`Number` = ?
			WHERE 
					`cars`.`Id` = ?
		",
		array('si', $_POST["number"], $_POST["id"]), 
		true
	);
?>