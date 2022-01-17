<?php session_start(); error_reporting( E_ERROR );
	
	if(isset($_POST['log_off'])) 
	{
		unset ($_SESSION['roleId']);
	}
	if(!isset($_SESSION['roleId']))
	{
		header('Location: login.html');
	}else{
		include_once ('layout.php');
	}
?>
