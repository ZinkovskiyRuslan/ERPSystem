<?php //http://erpelement.ru/api/system/sendTelegram.php?message=Ручая%20заправка%20на%200л.%20за%2045сек&token=

	if(!isset($_GET['token']) || empty($_GET['token']))
	{
		return null;
	}

	include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
	include_once ($_SERVER['DOCUMENT_ROOT'].'/api/shared/functions/getCompanyByToken.php');

	$companyList = getCompanyByToken($_GET['token']);

	if(count($companyList) == 1)
	{
		$company = $companyList[0];
		if(strpos($_GET["message"], "л. за ") !== false){
			$fuelFill = explode("Ручая заправка на ", explode("л. за ", $_GET["message"])[0])[1];
			$removed = 0;
			//Если заправка на 0 л. то пишем в базу, но нигде не отображаем
			if($fuelFill == 0)
				$removed = 1;
			include_once ($_SERVER['DOCUMENT_ROOT'].'/db.php');
			$result = ExecSQL(
				array('anonymous'),
				"
					INSERT INTO 
						`fuelinformations`
						(
							`Blocked`,
							`Fuel`,
							`FuelFill`, 
							`FuelFillDate`,
							`Removed`,
							`FillType`
						)
					VALUES
					(
						1,
						?,
						?,
						current_timestamp(),
						?,
						1
					);
				",
				array('iii', $fuelFill, $fuelFill, $removed),
				true
			);
		}

		$method = "/sendMessage";
		$url = "https://api.telegram.org/bot".$company["TgToken"].$method."?chat_id=".$company["TgChatId"]."&text=".$_GET['message'];

		$options = array(
			'https' => array(
				'header' => "Content-type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST'
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { 
			//Handle error
		}
		//var_dump($result);
	}else{
		echo("Error");
	}
?>