<?
	function getFuelInformations($db)
	{
		$array = array();
		$result = "";
		//создание подготавливаемого запроса
		$stmt = $db->prepare
		("
			SELECT
					fi.Id,
					u.DeviceId,
					u.FirstName,
					u.MiddleName,
					u.LastName,
					fi.Fuel,
					IFNULL(fi.FuelFill,0),
					fi.Blocked,
					fi.FuelFillDate
			FROM
					`fuelinformations` fi,
					`users` u
			WHERE
					fi.UserId = u.Id
			Order By
					fi.Id Desc
			LIMIT 100
		");
		
		//выполнение запроса
		$stmt->execute();

		//связывание переменных с результатами запроса
		$stmt->bind_result($res1, $res2, $res3, $res4, $res5, $res6, $res7, $res8, $res9);

		while ($stmt->fetch()) {
			array_push(
						$array,
						array(
								"Id"			=>$res1,
								"DeviceId"		=>$res2,
								"FIO"			=>$res5." ".$res3." ".$res4,
								"FirstName"		=>$res3,
								"MiddleName"	=>$res4,
								"LastName"		=>$res5,
								"Fuel"			=>$res6,
								"FuelFill"		=>$res7,
								"Blocked"		=>$res8,
								"FuelFillDate"	=>$res9
							)
						);
		}

		return $array;
	}
?>