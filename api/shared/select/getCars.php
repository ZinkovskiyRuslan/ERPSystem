<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('manager'),
		"
			SELECT
					c.Id		as value,
					c.Number	as label
			FROM
					`cars` c
			Order By
					c.Number Desc
		",
		array(), 
		false
	);
?>