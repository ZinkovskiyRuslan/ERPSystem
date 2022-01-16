<?	
	session_start(); error_reporting( E_ERROR );
	$importDbActions = array("login");
	include ('../../db.php');
	$result = login($db, $_POST["login"], $_POST["password"]);
	if($result.id)
	{
		$_SESSION['id'] = $result.id;
		$_SESSION['roleId'] = $result.roleId;
	}
	http_response_code(200);
	echo json_encode($result);
?>