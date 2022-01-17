<?php
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		"
			SELECT
					u.Id	as value,
					CONCAT(u.LastName , ' ', u.FirstName, ' ', u.MiddleName) as label
			FROM
					`users` u
			Order By
					u.LastName Desc
		",
		array(), 
		false
	);
?>