<?php session_start();
	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('admin'),
		"
			SELECT
					i.Id,
					i.Sensor,
					i.ValueCurrent,
					i.ValueTo,
					i.SetDate,
					i.UpdateDate
			FROM
					`incubator` i
			Order By
					i.Id
		",
		array(), 
		false
	);
?>