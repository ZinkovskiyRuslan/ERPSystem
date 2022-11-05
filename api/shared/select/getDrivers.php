<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('manager'),
		"
			SELECT
					u.Id	as value,
					CONCAT(u.LastName , ' ', u.FirstName, ' ', u.MiddleName) as label
			FROM
					`users` u
			WHERE
					u.Blocked = 0 AND
					u.Removed = 0 AND
					u.Id <> 1
			Order By
					u.LastName Asc
		",
		array(), 
		false
	);
?>