<?	session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	$result = ExecSQL(
		array('admin'),
		"
			SELECT 
					u.Id			as id,
					u.Password		as password,
					r.Name			as role,
					u.LastName		as lastName,
					u.FirstName		as firstName,
					u.MiddleName	as middleName
			FROM 
					`users`	u,
					`roles`	r
			WHERE 
					u.UserName	= ?		AND 
					u.Blocked	= 0		AND
					u.RoleId	= r.Id
			LIMIT 1
		",
		array('s', $_POST["userName"]),
		false
	);
	if(count($result[1]) == 1);
	{
		if($result[1][0]['id'])
		{
			$_SESSION['id'] = $result[1][0]['id'];
			$_SESSION['role'] = $result[1][0]['role'];
			$_SESSION['lastName'] = $result[1][0]['lastName'];
			$_SESSION['firstName'] = $result[1][0]['firstName'];
			$_SESSION['middleName'] = $result[1][0]['middleName'];
		}
	}
	http_response_code(200);
	echo json_encode(array('id'=>$result[1][0]['id'],'role'=>$result[1][0]['role'],));
?>