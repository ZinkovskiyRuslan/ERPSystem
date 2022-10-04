<?php
$backup_folder = '/home/my_site/backup';    // куда будут сохранятся файлы
$backup_name = 'my_site_backup_' . date("Y-m-d");    // имя архива
$dir = '/home/my_site/www';    // что бэкапим
$delay_delete = 30 * 24 * 3600;    // время жизни архива (в секундах)

$db_host = 'example.com';
$db_user = 'root';
$db_password = 'password';
$db_name = 'database';

$mail_to = 'zinkovsky.ruslan@yandex.ru';
$mail_subject = 'Site backup';
$mail_message = '';
$mail_headers = 'MIME-Version: 1.0' . "\r\n";
$mail_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$mail_headers .= 'To: me <zinkovsky.ruslan@yandex.ru>' . "\r\n";
$mail_headers .= 'From: my_site <http://erpelement.ru>' . "\r\n";
/*
function backupFiles($backup_folder, $backup_name, $dir)
{
    $fullFileName = $backup_folder . '/' . $backup_name . '.tar.gz';
    shell_exec("tar -cvf " . $fullFileName . " " . $dir . "/* ");
    return $fullFileName;
}

function backupDB($backup_folder, $backup_name)
{
    $fullFileName = $backup_folder . '/' . $backup_name . '.sql';
    $command = 'mysqldump -h' . $db_host . ' -u' . $db_user . ' -p' . $db_password . ' ' . $db_name . ' > ' . $fullFileName;
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

$start = microtime(true);    // запускаем таймер

$deleteOld = deleteOldArchives($backup_folder, $delay_delete);    // удаляем старые архивы
$doBackupFiles = backupFiles($backup_folder, $backup_name, $dir);    // делаем бэкап файлов
$doBackupDB = backupDB($backup_folder, $backup_name);    // и базы данных

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
*/
?>