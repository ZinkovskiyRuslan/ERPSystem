<?php
	$importDbActions = array("setFuelInfo");
	include_once('../../db.php');
	echo(setFuelInfo($db, $_GET['id'], $_GET['fuel']));
?>
