<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('manager'),
		"
			UPDATE
					`users`
			SET
					`UserName` = ?,
					`FirstName` = ?,
					`MiddleName` = ?,
					`LastName` = ?,
					`Email` = ?,
					`Phone` = ?,
					`Blocked` = ?,
					`RoleId` = ?
			WHERE 
					`users`.`Id` = ? 
		",
		array('ssssssiii', $_POST["userName"], $_POST["firstName"], $_POST["middleName"], $_POST["lastName"], $_POST["email"], $_POST["phone"], $_POST["blocked"], $_POST["roleId"], $_POST["id"]), 
		true
	);
	
	if($_POST["deviceId"] > 0)
	{
		jsonExecSQL(
			array('manager'),
			"
				UPDATE 
						`users`
				SET 
						`DeviceId` = ?
				WHERE 
						`users`.`Id` = ?;
			",
			array('si', $_POST["deviceId"], $_POST["id"]),
			true
		);
	}
?>