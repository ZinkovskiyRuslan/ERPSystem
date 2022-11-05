<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('manager'),
		"
			SELECT
					d.Id		as value,
					d.Device 	as label
			FROM
					`devices` d left join `users` u ON(d.Id = u.DeviceId)
			WHERE
					d.Removed = 0 	AND
					u.Id IS NULL	AND
					d.Id > 3
			Order By
					d.Device Desc
		",
		array(), 
		false
	);
?>