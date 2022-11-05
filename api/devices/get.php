<?php session_start(); 
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	
	jsonExecSQL(
		array('admin'),
		"
			SELECT
					d.Id,
					d.Device,
					d.CreationDate,
					u.LastName,
					u.FirstName,
					u.MiddleName
			FROM
					`devices` d left join `users` u ON(d.Id = u.DeviceId)
			WHERE
					d.Removed = 0
			Order By
					d.Id Desc
		",
		array(), 
		false
	);
?>