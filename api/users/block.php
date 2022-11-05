<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('admin'),
		"
			UPDATE
					`users`
			SET
					`Blocked` = '1',
					`DeviceId` = NULL					
			WHERE 
					`users`.`Id` = ?
		",
		array('i', $_POST["id"]),
		true
	);
?>