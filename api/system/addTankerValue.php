<?php // http://erpelement.ru/api/system/addTankerValue.php?controllerId=1&sensorId=1&value=20000;25000;30000;35000&token=

	if(!isset($_GET['token']) || empty($_GET['token']))
	{
		return null;
	}

	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	include_once ($_SERVER['DOCUMENT_ROOT'].'/api/shared/functions/getCompanyByToken.php');

	if(count(getCompanyByToken($_GET['token'])) == 1)
	{
		$values = explode(";", $_GET['value']);
		$cnt = count($values);
		foreach ($values as $value) { 
			$cnt--;
			jsonExecSQL(
				array('anonymous'),
				"
					INSERT INTO `tanker` 
						(
							`Id`, 
							`CreationDate`,
							`SensorId`,
							`Value`
						)
					VALUES
						(
							NULL,
							DATE_ADD(DATE_ADD(current_timestamp(), INTERVAL -? MINUTE ), INTERVAL - SECOND(current_timestamp()) SECOND ),
							?,
							?
						)
				",
				array('iii', $cnt, $_GET['SensorId'], $value),
				true
			);
		}
		
	}
?>
