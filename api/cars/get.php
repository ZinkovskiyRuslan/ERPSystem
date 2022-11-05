<?php session_start(); 
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	
	jsonExecSQL(
		array('admin'),
		"
			SELECT
					c.Id,
					c.Number,
					u.LastName,
					u.FirstName,
					u.MiddleName
			FROM
					`cars` c left join `users` u ON(c.Id = u.CarId)
			WHERE
					c.Removed = 0
			Order By
					c.Id Desc
		",
		array(), 
		false
	);
?>