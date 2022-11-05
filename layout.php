<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
		<!-- import CSS -->
		<!-- <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">--> 
		<link rel="stylesheet" href="https://unpkg.com/element-ui@2.15.10/lib/theme-chalk/index.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	</head>

	<!-- import Vue before Element -->
	<script src="https://unpkg.com/vue@2.6.14/dist/vue.js"></script>
	<!-- import JavaScript -->
	<script src="https://unpkg.com/element-ui/lib/index.js"></script>

	<script src="//unpkg.com/element-ui/lib/umd/locale/ru-RU.js"></script>

	<script>
		ELEMENT.locale(ELEMENT.lang.ruRU)
	</script>
	
	<script src="https://cdn.jsdelivr.net/npm/v-mask/dist/v-mask.min.js"></script>
	<script>
		Vue.directive('mask', VueMask.VueMaskDirective);
	</script>

	<script src="https://kit.fontawesome.com/1eaf07877b.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="css/fontawesome/all.css" >
	<link rel="stylesheet" href="css/site.css">
	<script>
		erp = {
			post:function(url, data, callback)
			{
				$.ajax({
						url: url,
						data: data,
						method: "post"
					}).done(function (data) {
						callback($.parseJSON(data));
					}).fail(function (exception) {
						console.log('erp.post call error')
						console.log('url = ' + url);
						console.log('data = ' + data);
						console.log('exception = ' + JSON.stringify(exception));
					})
			},
			validate:function(self, callback){
				let isValid = true;
				self.$refs.form.validate((valid) => {
					if (!valid) {
						self.$message({
							type: 'warning',
							message: 'Не все обязательные поля заполнены!',
						});
					}
					isValid = valid;
				});
				callback(isValid);
			},
			showTrueFalse(value) {
				console.log(value)
				return value == true ? 'Да' : 'Нет';
			},
			enums:{
				yesNo:
					[
						{"value":0,"label":"Нет"},
						{"value":1,"label":"Да"},
					],
				roles:
					[
						{"value":1,"label":"Администратор"},
						{"value":2,"label":"Водитель"},
						{"value":3,"label":"Менеджер"},
					],
			},
			dateToSqlFormat(date) {
				const m = date.getMonth() + 1;
				const space = ' ';
				return date.getFullYear() + "-" + m + "-" + date.getDate() + space + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
			},
			dateToString(date) {
				const m = date.getMonth() + 1;
				const space = ' ';
				if (date.getDate() < 10) 
					return date.getFullYear() + "-" + m + "-0" + date.getDate();
				else
					return date.getFullYear() + "-" + m + "-" + date.getDate();
			}
		}
	</script>
<style>
	html, body { 
		height: 100%; 
	}
	.button{
		width: 80px;
		border-radius: 10px;
		height: 35px;
		border: none;
		cursor: pointer;
	}
	.el-table .cell {
		word-break:break-word;
	}
	.el-table .el-table__cell {
		padding: 4px 0;
	}
	.el-select{
		width: 100%;
	}
	.el-form-item__label {
		text-align: left;
	}
	.el-table .el-table__cell{
		text-align: center;
	}
	.el-dialog {
	width: fit-content;
	padding: 0px 10px;
}
</style>

	<body style="margin:0px;">
		<div style="width:100%; min-height: 100%;">
			<div style="width:100%; height:60px; display: flex;">
				<div style="padding-top: 10px; padding-left: 10px;">
					<a id="company-logo" class="navbar-brand" href="/">
						<img src="img/ErpSystemLogo.png" style="height:52px;" alt="logo ERPElement">
					</a>
				</div>
				<div style="position: absolute; right:20px; top:20px;">
					
					<form action="#" method="post" style="display: flex;">
						<div style="display: flex; flex-direction: column; text-align: center;">
							<div style="display: inline-block"><?echo($_SESSION['lastName']);?></div><br>
							<div style="display: inline-block"><?echo($_SESSION['firstName'].' '.$_SESSION['middleName']);?></div> 
						</div>
						<div style="margin-left:10px; margin-top:3px;">
							<button class="button" type="submit" name="logOff">Выйти</button>
						</div>
					</form>
				</div>
			</div>
			<div style="display: flex; width:100%; height:100%;">
				<div style="width:120px; height:100%;">
					<div style="padding-top:50px;padding-left: 10px;">
						<?
							//ToDo Вынести конфиг в БД
							if($_SESSION['role'] == 'admin')
								$pages = array("devices", "users", "cars","fuelInformations", "bunker", "incubator", "tanker", "raspberryPi, fuelInformationstest");
							if($_SESSION['role'] == 'manager')
								$pages = array("fuelInformations","tanker");
							if(in_array("devices", $pages))			{echo "<a href=\"/index.php?page=devices\">Устройства</a><br><br>";}
							if(in_array("users", $pages))			{echo "<a href=\"/index.php?page=users\">Сотрудники</a><br><br>";}
							if(in_array("cars", $pages))			{echo "<a href=\"/index.php?page=cars\">Транспорт</a><br><br>";}
							if(in_array("fuelInformations", $pages)){echo "<a href=\"/index.php?page=fuelInformations\">Заправочная станция</a><br><br>";}
							if(in_array("bunker", $pages))			{echo "<a href=\"/index.php?page=bunker\">Силосный бак</a><br><br>";}
							if(in_array("incubator", $pages))		{echo "<a href=\"/index.php?page=incubator\">Инкубатор</a><br><br>";}
							if(in_array("tanker", $pages))			{echo "<a href=\"/index.php?page=tanker\">Заправщики</a><br><br>";}
							if(in_array("raspberryPi", $pages))		{echo "<a href=\"/index.php?page=raspberryPi\">RaspberryPi</a><br><br>";}
						?>
					</div>
				</div>
				<div style="width:90%; height:100%;">
					<div style="padding:5px; padding-top:10px;">
						<?
							if($_GET['page'] == "") include_once('api/fuelInformations/index.php');
							if($_GET['page'] == "fuelInformationstest") include_once('api/fuelInformations/indexx.php');
							else include_once('api/'.$_GET['page'].'/index.php');
						?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>