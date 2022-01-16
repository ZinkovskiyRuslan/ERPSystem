<?php
	include ('../../../db.php');
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
					c.Id,
					c.Number
			FROM
					`cars` c
			Order By
					c.Number Desc
		");
		
		//выполнение запроса
		$stmt->execute();

		//связывание переменных с результатами запроса
		$stmt->bind_result($res1, $res2);

		while ($stmt->fetch()) {
			array_push(
						$array,
						array(
								"value"	=>$res1,
								"label"	=>$res2
							)
						);
		}

		return $array;
	}
?>