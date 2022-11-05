<?php session_start();
	
	if(isset($_POST['logOff'])) 
	{
		unset ($_SESSION['role']);
	}
	if(!isset($_SESSION['role']))
	{
		header('Location: login.html');
	}else{
		include_once ('layout.php');
	}
?>
