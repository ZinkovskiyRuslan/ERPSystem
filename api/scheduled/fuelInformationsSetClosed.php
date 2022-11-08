<?	// http://erpelement.ru/api/scheduled/fuelInformationsSetClosed.php?token=

	if(!isset($_GET['token']) || empty($_GET['token']))
	{
		return null;
	}

	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	include_once ($_SERVER['DOCUMENT_ROOT'].'/api/shared/functions/getCompanyByToken.php');

	if(count(getCompanyByToken($_GET['token'])) == 1)
	{
		echo("Updated rows count ");
		jsonExecSQL(
			array('anonymous'),
			"
				UPDATE
						`fuelinformations`
				SET
						`Blocked` = 1,
						`Closed` = 1
				WHERE 
						`fuelinformations`.`Closed` = 0
			",
			array(null,null), 
			true
		);
	}
?>