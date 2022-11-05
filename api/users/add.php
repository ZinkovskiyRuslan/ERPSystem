<?php session_start();	
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('admin'),
		"
			INSERT INTO 
				`users`
				(
					`UserName`,
					`FirstName`,
					`MiddleName`,
					`LastName`,
					`Email`,
					`Phone`,
					`Blocked`,
					`DeviceId`,
					`RoleId`
				)
			VALUES
			(
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				NULL,
				?
			);
		",
		array('ssssssii', $_POST["userName"], $_POST["firstName"], $_POST["middleName"], $_POST["lastName"], $_POST["email"], $_POST["phone"], $_POST["blocked"], $_POST["roleId"]),
		true
	);
	if($_POST["deviceId"] != "")
	{
		echo($_POST["deviceId"]);
		jsonExecSQL(
			array('manager'),
			"
				UPDATE 
						`users`
				SET 
						`DeviceId` = ?
				WHERE 
						`users`.`UserName` = ?;
			",
			array('ss', $_POST["deviceId"], $_POST["userName"]),
			true
		);
	} 
	if($_POST["password"] != "" and $_POST["password"] != "******")
	{
		$pass = password_hash($_POST["password"], PASSWORD_BCRYPT);
		jsonExecSQL(
			array('manager'),
			"
				UPDATE 
						`users`
				SET 
						`Password` = ?
				WHERE 
						`users`.`UserName` = ?;
			",
			array('ss', $pass, $_POST["userName"]),
			true
		);
	}
?>