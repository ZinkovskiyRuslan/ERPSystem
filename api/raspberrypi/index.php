<?php
	$info = json_decode(file_get_contents('array.json'), true);
?>
<table border="1" style="border-collapse: collapse; border: 1px solid black;">
    <thead>
		<tr>
			<td>Время</td>
			<td>№ Raspberry Pi</td>
			<td>Состояние устройства</td>
			<td>Сигнал датчика №1</td>
			<td>Сигнал датчика №2</td>
			<td>Сигнал датчика №3</td>
		</tr>
    </thead>
    <tbody>
<?php
foreach ($info as $item)
{
    echo 
	'<tr>
		<td>'.$item['creationDate'].'</td>
        <td>'.$item['name'].'</td>
		<td>'.$item['status'].'</td>
        <td>'.$item['sensor_1'].'</td>
        <td>'.$item['sensor_2'].'</td>
        <td>'.$item['sensor_3'].'</td>
    </tr>';
}
?>
    </tbody>
</table>