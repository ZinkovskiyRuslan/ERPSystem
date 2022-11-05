<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('admin'),
		"
			SELECT
					u.Id,
					u.UserName,
					u.FirstName,
					u.MiddleName,
					u.LastName,
					CONCAT(u.LastName, ' ', u.FirstName, ' ', u.MiddleName) as FIO,
					u.Email,
					u.Phone,
					u.Blocked,
					u.DeviceId,
					d.Device,
					u.RoleId,
					u.CreationDate
			FROM
					`users` u Left Join `devices` d on (u.DeviceId = d.Id)
			WHERE
					u.Removed = 0
			Order By
					u.Id Desc
			LIMIT 100
		",
		array(), 
		false
	);
?>