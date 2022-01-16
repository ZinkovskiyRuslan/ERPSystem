<?php
	include ('../../db.php');
	$result = getSqlResult($db, $_POST["userId"], $_POST["carId"], $_POST["fuel"]);
	http_response_code(200);
	echo json_encode($result);
	
	function getSqlResult($db, $p1, $p2, $p3)
	{
		$stmt = $db->prepare
		("
			INSERT INTO 
				`fuelinformations`
				(
					`UserId`,
					`CarId`,
					`Fuel`
				)
			VALUES
			(
				?,
				?,
				?
			);
		");
		$stmt->bind_param("iii", $p1, $p2, $p3);
		if ($stmt->execute()){
			return true;
		}
		return false;
	}
?>