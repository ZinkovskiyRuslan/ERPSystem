<?php
	include ('../../db.php');
	$result = getSqlResult($db, $_POST["id"]);
	http_response_code(200);
	echo json_encode($result);

	function getSqlResult($db, $p1)
	{
		$stmt = $db->prepare
		("
			UPDATE
					`fuelinformations`
			SET
					`Removed` = '1'
			WHERE 
					`fuelinformations`.`Id` = ?
		");
		$stmt->bind_param("i", $p1);
		if ($stmt->execute()){
			return true;
		}
		return false;
	}
?>