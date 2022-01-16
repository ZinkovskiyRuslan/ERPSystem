<?
	function login($db, $login, $password)
	{
		//создание подготавливаемого запроса
		$stmt = $db->prepare
		("
			SELECT 
					Id,
					RoleId
			FROM 
					`users` 
			WHERE 
					UserName = ? AND 
					Password = ? AND 
					Blocked = 0
			LIMIT 1
		");

		//связывание параметров с метками
		$stmt->bind_param("ss", $login, $password);

		//выполнение запроса
		$stmt->execute();

		//связывание переменных с результатами запроса
		$stmt->bind_result($Id, $RoleId);

		// Выбрать значения
		$stmt->fetch();

		return (array('id' => $Id, 'roleId' => $RoleId));
	}
?>
