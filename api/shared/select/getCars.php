<?php
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
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