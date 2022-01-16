<?php
	include ('../../db.php');
	$result = getSqlResult($db);
	http_response_code(200);
	echo json_encode($result);

	function getSqlResult($db)
	{
		$array = array();
		$result = "";
		//создание подготавливаемого запроса
		$stmt = $db->prepare
		("
			SELECT
					fi.Id,
					fi.UserId,
					fi.CarId,
					u.FirstName,
					u.MiddleName,
					u.LastName,
					u.DeviceId,
					c.Number,
					fi.Fuel,
					IFNULL(fi.FuelFill,0),
					fi.Blocked,
					fi.FuelFillDate
			FROM
					`fuelinformations` fi LEFT JOIN `cars` c ON fi.CarId = c.Id,
					`users` u
			WHERE
					fi.Removed = 0 AND
					fi.UserId = u.Id
			Order By
					fi.Id Desc
			LIMIT 100
		");
		
		//выполнение запроса
		$stmt->execute();

		//связывание переменных с результатами запроса
		$stmt->bind_result($res1, $res2, $res3, $res4, $res5, $res6, $res7, $res8, $res9, $res10, $res11, $res12);

		while ($stmt->fetch()) {
			array_push(
						$array,
						array(
								"Id"			=>$res1,
								"UserId"		=>$res2,
								"CarId"			=>$res3,
								"FIO"			=>$res6." ".$res4." ".$res5,
								"FirstName"		=>$res4,
								"MiddleName"	=>$res5,
								"LastName"		=>$res6,
								"DeviceId"		=>$res7,
								"Number"		=>$res8,
								"Fuel"			=>$res9,
								"FuelFill"		=>$res10,
								"Blocked"		=>$res11,
								"FuelFillDate"	=>$res12
							)
						);
		}

		return $array;
	}
?>