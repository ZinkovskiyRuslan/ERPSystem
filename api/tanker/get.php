<?php session_start();

	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	jsonExecSQL(
		array('manager'),
		"
			SELECT 
					t.Id,
					dbm.date as CreationDate,
					/*((t.Value-62240)*-1)/2.80 as Value,*/ 
					/*((t.Value-945000)*-1)/215 as Value,*/ 
					/*((t.Value-945000)*-1)/46 as Value, 777*/ 
					((t.Value-778600)*-1)/46 as Value,
					t.Value as Dut
			FROM
					datebyminute dbm LEFT JOIN `tanker` t ON (dbm.date = t.CreationDate AND t.SensorId = ?)
			WHERE
					dbm.date > ? AND
					dbm.date < ? 
			Order By
					dbm.Id desc
			/*LIMIT 144000 */
		",
		array('iss',$_POST["sensorId"],$_POST["dateBegin"],$_POST["dateEnd"]), 
		false
	);
	/*
					dbm.date > DATE_ADD(?, INTERVAL -1 HOUR ) AND
					dbm.date < DATE_ADD(?, INTERVAL -1 HOUR ) 
	*/
?>
