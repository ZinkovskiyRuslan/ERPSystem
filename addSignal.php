<?php
//9271031610.myjino.ru/addSignal.php?creationDate=16.06.2021 17:30&name=Raspberry Pi №2&status=Start programm&sensor_1=48.2&sensor_2=&sensor_3=
echo "</br> CreationDate=";
echo $_GET['creationDate'];
echo "</br> Name=";
echo $_GET['name'];
echo "</br> Status=";
echo $_GET['status'];
echo "</br>Sensor_1=";
echo $_GET['sensor_1'];
echo "</br>Sensor_2=";
echo $_GET['sensor_2'];
echo "</br>Sensor_3=";
echo $_GET['sensor_3'];
echo "</br>";

$info_item =
    array(
		'creationDate'=>$_GET['creationDate'],
        'name'=>$_GET['name'],
		'status'=>$_GET['status'],
		'sensor_1'=>$_GET['sensor_1'],
		'sensor_2'=>$_GET['sensor_2'],
		'sensor_3'=>$_GET['sensor_3'],
    );

$info = json_decode(file_get_contents('array.json'), true);

if ($info == null)
	$info = array();

array_unshift($info, $info_item);
var_dump($info);
file_put_contents("array.json",json_encode($info));
?>