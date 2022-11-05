<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('admin'),
		"
			UPDATE
					`incubator`
			SET
					`ValueTo` = ?,
					`SetDate` = current_timestamp()
			WHERE 
					`incubator`.`Id` = ?
		",
		array('ii', $_POST["valueTo"], $_POST["id"]), 
		true
	);
?>