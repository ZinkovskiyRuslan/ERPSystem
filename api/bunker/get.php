<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('admin'),
		"
			SELECT
					b.Id,
					b.Value,
					b.CreationDate
			FROM
					`bunker` b
			Order By
					b.Id Desc
		",
		array(), 
		false
	);
?>