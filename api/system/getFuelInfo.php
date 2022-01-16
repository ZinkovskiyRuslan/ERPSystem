<?php
	$importDbActions = array("getFuelInfo");
	include_once('../../db.php');
	echo(getFuelInfo($db));
?>
