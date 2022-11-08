<?php
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
	
	include_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');


	//echo __FILE__; 
	$backup_folder = $_SERVER['DOCUMENT_ROOT'].'/backup';	// куда будут сохранятся файлы
	$backup_name = 'my_site_backup_' . date("Y-m-d");		// имя архива
	$dir = $_SERVER['DOCUMENT_ROOT'];						// что бэкапим
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
		shell_exec("tar -cvf " . $fullFileName . " --exclude " . $backup_folder . " " . $dir . "/* ");
		//$cmd = "tar -cvf " . $fullFileName . " --exclude " . $backup_folder . " " . $dir . "/api/* ";
		//echo $cmd;
		//shell_exec($cmd);
		return $fullFileName;
	}

	function backupDB($backup_folder, $backup_name)
	{
		$fullFileName = $backup_folder . '/' . $backup_name . '.sql';
		$command = 'mysqldump -h' . DBHOST . ' -u' . DBUSER . ' -p' . DBPWD . ' ' . DBNAME . ' > ' . $fullFileName;
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

	//mail($mail_to, $mail_subject, $mail_message, $mail_headers);    // и отправляем письмо


?>