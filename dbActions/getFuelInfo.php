<?
	function getFuelInfo($db)
	{
		$result = "";

		//создание подготавливаемого запроса
		$stmt = $db->prepare
		("
			SELECT
					u.DeviceId,
					fi.Fuel - IFNULL(fi.FuelFill,0)
			  FROM
					`fuelinformations` fi,
					`users` u
			 WHERE
					fi.UserId = u.Id	AND
					fi.Fuel > IFNULL(fi.FuelFill,0) 
		");

		//выполнение запроса
		$stmt->execute();

		//связывание переменных с результатами запроса
		$stmt->bind_result($res1, $res2);

		/* Выбрать значения */
		while ($stmt->fetch()) {
			$result = $result . $res1 . "|" . $res2 . ";";
		}

		return $result;
	}
?>