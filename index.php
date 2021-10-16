<?php session_start(); error_reporting( E_ERROR );?>
<?php
	if(isset($_POST['log_off'])) 
	{
		unset ($_SESSION['id']);	
	}
	if(!isset($_SESSION['id']))
	{
		include ('login.html');
		if(isset($_POST['log_in'])) 
		{ 
			$login = $_POST['login']; 
			$password = $_POST['password'];
			
			if($login.$password == "adminadmin" || $login.$password == "Adminadmin")
			{
				$_SESSION['id'] = "admin";
				header('Location: http://9271031610.myjino.ru/');
			}
			
		}
	}else{
		include ('report.php');
	}
?>
