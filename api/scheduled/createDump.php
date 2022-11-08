<?php
	include_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
	/*
	  $filename = $_SERVER['DOCUMENT_ROOT'].'/login.html'; //Имя файла для прикрепления
	  $to = "r@yandex.ru"; //Кому
	  $from = "r@yandex.ru"; //От кого
	  $subject = "Test"; //Тема
	  $message = "Текстовое сообщение"; //Текст письма
	  $boundary = "---"; //Разделитель
	  // Заголовки 
	  $headers = "From: $from\nReply-To: $from\n";
	  $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";
	  $body = "--$boundary\n";
	  // Присоединяем текстовое сообщение 
	  $body .= "Content-type: text/html; charset='utf-8'\n";
	  $body .= "Content-Transfer-Encoding: quoted-printablenn";
	  $body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($filename)."?=\n\n";
	  $body .= $message."\n";
	  $body .= "--$boundary\n";
	  $file = fopen($filename, "r"); //Открываем файл
	  $text = fread($file, filesize($filename)); //Считываем весь файл
	  fclose($file); //Закрываем файл
	  echo $text."teext";
	  // Добавляем тип содержимого, кодируем текст файла и добавляем в тело письма 
	  $body .= "Content-Type: application/octet-stream; name==?utf-8?B?".base64_encode($filename)."?=\n";
	  $body .= "Content-Transfer-Encoding: base64\n";
	  $body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($filename)."?=\n\n";
	  $body .= chunk_split(base64_encode($text))."\n";
	  $body .= "--".$boundary ."--\n";
	  mail($to, $subject, $body, $headers); //Отправляем письмо
	*/
	//echo __FILE__; 
	echo($_SERVER['DOCUMENT_ROOT']);  
	$backup_folder = $_SERVER['DOCUMENT_ROOT'].'/backup';	// куда будут сохранятся файлы
	$backup_name = 'my_site_backup_' . date("Y-m-d");		// имя архива
	$dir = $_SERVER['DOCUMENT_ROOT'].'/api';				// что бэкапим
	$delay_delete = 30 * 24 * 3600;							// время жизни архива (в секундах)

	$mail_to = EMAIL;
	$mail_subject = 'Site backup';
	$mail_message = '';
	$mail_headers = 'MIME-Version: 1.0' . "\r\n";
	$mail_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$mail_headers .= 'To: me <'.$mail_to.'>' . "\r\n";
	$mail_headers .= 'From: Backup agent <BackupAgent>' . "\r\n";

	function backupFiles($backup_folder, $backup_name, $dir)
	{
		$fullFileName = $backup_folder . '/' . $backup_name . '.tar.gz';
		shell_exec("tar -cvfP " . $fullFileName . " -C / " . $dir . "/* ");
		return $fullFileName;
	}

	function backupDB($backup_folder, $backup_name)
	{
		$fullFileName = $backup_folder . '/' . $backup_name . '.sql';
		$command = "test"; //'mysqldump -h' . DBHOST . ' -u' . DBUSER . ' -p' . DBPWD . ' ' . DBNAME . ' > ' . $fullFileName;
		shell_exec($command);
		return $fullFileName;
	}

	function deleteOldArchives($backup_folder, $delay_delete)
	{
		$this_time = time();
		$files = glob($backup_folder . "/*.tar.gz*");
		$deleted = array();
		foreach ($files as $file) {
			if ($this_time - filemtime($file) > $delay_delete) {
				array_push($deleted, $file);
				unlink($file);
			}
		}
		return $deleted;
	}
	$start = microtime(true);// запускаем таймер

	$deleteOld = deleteOldArchives($backup_folder, $delay_delete);			// удаляем старые архивы
	$doBackupFiles = backupFiles($backup_folder, $backup_name, $dir);		// делаем бэкап файлов

	$doBackupDB = backupDB($backup_folder, $backup_name);					// и базы данных
	// добавляем в письмо отчеты
	if ($doBackupFiles) {
		$mail_message .= 'site backuped successfully<br/>';
		$mail_message .= 'Files: ' . $doBackupFiles . '<br/>';
	}

	if ($doBackupDB) {
		$mail_message .= 'DB: ' . $doBackupDB . '<br/>';
	}

	if ($deleteOld) {
		foreach ($deleteOld as $val) {
			$mail_message .= 'File deleted: ' . $val . '<br/>';
		}
	}
	$time = microtime(true) - $start;     // считаем время, потраченое на выполнение скрипта
	$mail_message .= 'script time: ' . $time . '<br/>';

	mail($mail_to, $mail_subject, $mail_message, $mail_headers);    // и отправляем письмо

?>