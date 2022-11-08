<?php //http://erpelement.ru/api/system/addBunker.php?value=789&token=

	if(!isset($_GET['token']) || empty($_GET['token']))
	{
		return null;
	}

	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	include_once ($_SERVER['DOCUMENT_ROOT'].'/api/shared/functions/getCompanyByToken.php');

	if(count(getCompanyByToken($_GET['token'])) == 1)
	{
		jsonExecSQL(
			array('anonymous'),
			"
				INSERT INTO `bunker` 
					(
						`Id`, 
						`Value`, 
						`CreationDate`
					)
				VALUES
					(
						NULL,
						?,
						current_timestamp()
					)
			",
			array('i', $_GET['value']),
			true
		);
	}
?>
