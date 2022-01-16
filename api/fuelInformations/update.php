<?php
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	
	//$result = getSqlResult($db, $_POST["userId"], $_POST["carId"], $_POST["fuel"], $_POST["id"]);
	$result = execSQL(
	"
			UPDATE
					`fuelinformations`
			SET
					`UserId` = ?,
					`CarId` = ?,
					`Fuel` = ?
			WHERE 
					`fuelinformations`.`Id` = ?
		",
		
		array('iiii', $_POST["userId"], $_POST["carId"], $_POST["fuel"], $_POST["id"]),
		true  
		);
	http_response_code(200);
	echo json_encode($result);

	function getSqlResult($db, $p1, $p2, $p3, $p4)
	{
		include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
		$stmt = $db->prepare
		("
			UPDATE
					`fuelinformations`
			SET
					`UserId` = ?,
					`CarId` = ?,
					`Fuel` = ?
			WHERE 
					`fuelinformations`.`Id` = ?
		");
		$stmt->bind_param("iiii", $p1, $p2, $p3, $p4);
		if ($stmt->execute()){
			return true;
		}
		return false;
	}
	
	
 
?>