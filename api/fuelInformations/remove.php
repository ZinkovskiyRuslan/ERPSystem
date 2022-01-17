<?php
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
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