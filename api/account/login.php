<?	session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	$result = ExecSQL(
		"
			SELECT 
					Id as id ,
					RoleId as roleId
			FROM 
					`users` 
			WHERE 
					UserName = ? AND 
					Password = ? AND 
					Blocked = 0
			LIMIT 1
		",
		array('ss', $_POST["login"], $_POST["password"]),
		false
	);
	if($result[0]['id'])
	{
		$_SESSION['id'] = $result[0]['id'];
		$_SESSION['roleId'] = $result[0]['roleId'];
	}
	http_response_code(200);
	echo json_encode(array('id'=>$result[0]['id'],'roleId'=>$result[0]['roleId'],));
?>