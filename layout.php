<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
		<!-- import CSS -->
		<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	</head>

	<!-- import Vue before Element -->
	<script src="https://unpkg.com/vue/dist/vue.js"></script>
	<!-- import JavaScript -->
	<script src="https://unpkg.com/element-ui/lib/index.js"></script>
	
	<script src="https://kit.fontawesome.com/1eaf07877b.js" crossorigin="anonymous"></script>
	<link href="css/fontawesome/all.css" rel="stylesheet">
	<!--<link rel="stylesheet" href="css/login.css">-->
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
	<!--height: calc(100vh - 100px);-->
</style>

	<body style="margin:0px;">
		<div style="width:100%; min-height: 100%;">
			<div style="width:100%; height:74px; display: flex;">
				<div style="padding-top: 10px; padding-left: 10px;">
					<a id="company-logo" class="navbar-brand" href="/">
						<img src="img/ErpSystemLogo.png" style="height:52px;" alt="logo ERPElement">
					</a>
				</div>
				<div style="position: absolute; right:20px; top:20px;">
					<form action="#" method="post">
						<button class="button" type="submit" name="log_off">Выйти</button>
					</form>
				</div>
			</div>
			<div style="display: flex; width:100%; height:100%;">
				<div style="width:250px; height:100%;">
					<div style="padding-top:50px;padding-left: 10px;">
						<a href="/index.php?page=FuelInformations">Заправочная станция</a><br><br>
						<a href="/index.php?page=RaspberryPi">RaspberryPi</a>
					</div>
				</div>
				<div style="width:90%; height:100%;">
					<div style="padding:5px; padding-top:10px;">
						<?
							if($_GET['page'] == "FuelInformations" || $_GET['page'] == "")
								include_once('api/fuelInformations/index.php');
							if($_GET['page'] == "RaspberryPi")
								include_once('report.php');
						?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>