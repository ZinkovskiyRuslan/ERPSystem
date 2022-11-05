<div id="app">
	<template>
		<el-button type="text" @click="dialogVisible = true; dialogTitle = 'Добавить сотрудника'; ">
			<i class="fa-regular fa-address-card"></i>
			Добавить сотрудника
		</el-button>
		<el-dialog
			:title="dialogTitle"
			:visible.sync="dialogVisible"
			:before-close="handleClose">
				<el-form :model="form" ref="form" v-loading="loadingDialog">
					<el-form-item
						label="Логин"
						:label-width='formLabelWidth'
						:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
						prop="userName"
					>
						<el-input v-model="form.userName" autocomplete="off"></el-input>
					</el-form-item>		
					<el-form-item
						label="Пароль"
						:label-width='formLabelWidth'
						prop="password"
					>
						<el-input v-model="form.password" autocomplete="off"></el-input>
					</el-form-item>	
					<el-form-item
						label="Фамилия"
						:label-width='formLabelWidth'
						:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
						prop="lastName"
					>
						<el-input v-model="form.lastName" autocomplete="off"></el-input>
					</el-form-item>
					<el-form-item
						label="Имя"
						:label-width='formLabelWidth'
						:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
						prop="firstName"
					>
						<el-input v-model="form.firstName" autocomplete="off"></el-input>
					</el-form-item>
					<el-form-item
						label="Отчество"
						:label-width='formLabelWidth'
						:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
						prop="middleName"
					>
						<el-input v-model="form.middleName" autocomplete="off"></el-input>
					</el-form-item>
					<el-form-item
						label="E-mail"
						:label-width='formLabelWidth'
						prop="email"
					>
						<el-input v-model="form.email" autocomplete="off"></el-input>
					</el-form-item>
					<el-form-item
						label="Телефон"
						:label-width='formLabelWidth'
						prop="phone"
					>
						<el-input v-model="form.phone" autocomplete="off"></el-input>
					</el-form-item>
					<el-form-item 
						label="Заблокирован" 
						:label-width="formLabelWidth"
						:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
						prop="blocked"
					>
						<el-select v-model="form.blocked" placeholder="Укажите статус учётной записи">
							<el-option
								v-for="item in erp.enums.yesNo"
								:key="item.value"
								:label="item.label"
								:value="item.value">
							</el-option>
						</el-select>
					</el-form-item>
					<el-form-item 
						label="Id устройства" 
						:label-width="formLabelWidth"
						prop="deviceId"
					>
						<el-select v-model="form.deviceId" placeholder="Укажите Id устройства">
							<el-option
								v-for="item in devices"
								:key="item.value"
								:label="item.label"
								:value="item.value">
							</el-option>
						</el-select>
					</el-form-item>
					<el-form-item 
						label="Роль" 
						:label-width="formLabelWidth"
						:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
						prop="roleId"
					>
						<el-select v-model="form.roleId" placeholder="Укажите роль">
							<el-option
								v-for="item in erp.enums.roles"
								:key="item.value"
								:label="item.label"
								:value="item.value">
							</el-option>
						</el-select>
					</el-form-item>
				</el-form>
				<span slot="footer" class="dialog-footer">
					<el-button @click="dialogVisible = false; setDefaultForm();">Отмена</el-button>
					<el-button type="primary" @click="onApply">Сохранить</el-button>
				</span>
		</el-dialog>
		<el-table
			:data="tableData"
			border
			style="width:100%"
			v-loading="loading"
			:row-class-name="tableRowClassName">
			<el-table-column
				prop="Id"
				label="№ п/п"
				width="50"
				>
			</el-table-column>
			<el-table-column
				prop="Id"
				label="Действия"
				width="130">
				<template slot-scope="scope">
					<el-button style="margin-left: 10px;"
						@click.native.prevent="editRow(scope.$index, tableData)"
						type="text"
						size="small">
							<i class="fa fa-pencil" aria-hidden="true"></i>
					</el-button>					
					<el-button 
						@click.native.prevent="blockRow(scope.$index, tableData)"
						type="text"
						size="small">
							<i class="fa fa-ban" style = "color: blue;" aria-hidden="true"></i>
					</el-button>
					<el-button 
						@click.native.prevent="deleteRow(scope.$index, tableData)"
						type="text"
						size="small">
							<i class="fa fa-trash" style = "color: red;" aria-hidden="true"></i>
					</el-button>
					<el-button 
						@click.native.prevent="loginAs(scope.$index, tableData)"
						type="text"
						size="small">
							<i class="fa-solid fa-right-to-bracket"></i>
					</el-button>
				</template>
			</el-table-column>			
			<el-table-column
				prop="UserName"
				label="Логин"
				width="100">
			</el-table-column>
			<el-table-column
				prop="FIO"
				label="ФИО Водителя"
				width="250">
			</el-table-column>
			<el-table-column
				prop="Email"
				label="E-mail"
				width="140">
			</el-table-column>
			<el-table-column
				prop="Phone"
				label="Телефон"
				width="140">
			</el-table-column>
			<el-table-column
				prop="Blocked"
				label="Заблокирован"
				width="140">
				<template v-slot="{ row }">
					<span>
						{{ erp.showTrueFalse(row.Blocked) }}
					</span>
				</template>
			</el-table-column>
			<el-table-column
				prop="Device"
				label="Идентификатор"
				width="140">
			</el-table-column>
			<el-table-column
				prop="RoleId"
				label="Роль"
				width="140">
					<template v-slot="{ row }">
						<span>
							{{erp.enums.roles[row.RoleId-1].label}}
						</span>
					</template>
			</el-table-column>
			<el-table-column
				prop="CreationDate"
				label="Дата создания"
				width="140">
			</el-table-column>
		</el-table>
	</template>
</div>

<style>
	.el-table .blocked-row {
		background: #80808017;
	}
</style>
<script>

</script>
<script>
	var app = new Vue({
		el: '#app',
		data: {
			dialogVisible: false,
			dialogTitle: null,
			formLabelWidth: '120px',
			tableData: [{}],
			loading: false,
			loadingDialog: false,
			form: {
				id: null,
				userName: null,
				password: null,
				firstName: null,
				middleName: null,
				lastName: null,
				email: null,
				phone: null,
				blocked: 0,
				deviceId: null,
				roleId: 2,
			},
			erp: erp,
			devices: [],
			defaultForm: null,
		},
		created(){
			this.defaultForm = Object.assign({}, this.form);
			this.getCreated();
		},
		methods: {
			setDefaultForm()
			{
				this.form = Object.assign({}, this.defaultForm);
				this.$refs.form.resetFields();
			},
			getCreated(){
				let self = this;
				self.loading = true;
				$.ajax({
						url: "api/users/get.php",
						data: null,
						method: "post"
					}).done(function (data) {
						var json = $.parseJSON(data);
						if(json.length > 0)
						{
							self.tableData = json;
						}else{
							self.$message({
								type: 'warning',
								message: 'Список пуст',
							});
						}
					}).fail(function () {
						self.$message({
							type: 'error',
							message: 'Ошибка получения данных'
						});
					}).complete(function () {
						self.loading = false;
					});
				erp.post("api/shared/select/getDevices.php", null, (items) => self.devices = items);
			},
			tableRowClassName({row, rowIndex}){
				if (row.Blocked === 1){
					return 'blocked-row';
				}
				return '';
			},			
			blockRow(index, rows) {
				let self = this;
				self.$confirm('Вы действительно хотите заблокировать запись?', 'Внимание',
				{
					confirmButtonText: 'Да',
					cancelButtonText: 'Нет',
					type: 'warning',
				})
				.then(() => {
					$.ajax({
						url: "api/users/block.php",
						data: {id: rows[index].Id},
						method: "post"
					}).done(function (data) {
						var json = $.parseJSON(data);
						if(json == true)
						{
							rows[index].Blocked = 1;
							self.$message({
								type: 'succes',
								message: 'Запись успешно заблокирована'
							});
						}else{
							self.$message({
								type: 'error',
								message: 'Ошибка при блокировке записи'
							});
						}
					}).fail(function () {
						self.$message({
							type: 'error',
							message: 'Ошибка при блокировке записи'
						});
					}).complete(function () {
						self.loading = false;
					});
				})
				.catch(() => {
					this.$message({
						type: 'info',
						message: 'Отмена блокировки',
					});
				});
			},
			deleteRow(index, rows) {
				let self = this;
				self.$confirm('Вы действительно хотите удалить запись?', 'Внимание',
				{
					confirmButtonText: 'Да',
					cancelButtonText: 'Нет',
					type: 'warning',
				})
				.then(() => {
					$.ajax({
						url: "api/users/remove.php",
						data: {id: rows[index].Id},
						method: "post"
					}).done(function (data) {
						var json = $.parseJSON(data);
						if(json == true)
						{
							rows.splice(index, 1);
							self.$message({
								type: 'succes',
								message: 'Запись успешно удалена'
							});
						}else{
							self.$message({
								type: 'error',
								message: 'Ошибка при удалении записи'
							});
						}
					}).fail(function () {
						self.$message({
							type: 'error',
							message: 'Ошибка при удалении данных'
						});
					}).complete(function () {
						self.loading = false;
					});
				})
				.catch(() => {
					this.$message({
						type: 'info',
						message: 'Отмена удаления',
					});
				});
			},
			loginAs(index, rows) {
				let self = this;
				self.$confirm('Вы действительно хотите выполнить вход под сотрудником?', 'Внимание',
				{
					confirmButtonText: 'Да',
					cancelButtonText: 'Нет',
					type: 'warning',
				})
				.then(() => {
					$.ajax({
						url: "api/account/loginAs.php",
						data: {userName: rows[index].UserName},
						method: "post"
					}).done(function (data) {
						var json = $.parseJSON(data);
						location.reload();
					}).fail(function () {
						self.$message({
							type: 'error',
							message: 'Ошибка при входе под сотрудником'
						});
					}).complete(function () {
						self.loading = false;
					});
				})
				.catch(() => {
					this.$message({
						type: 'info',
						message: 'Отмена удаления',
					});
				});
			},
			editRow(index, rows){
				let self = this;
				self.dialogVisible = true;
				self.dialogTitle = "Редактирование данных по сотруднику";
				self.form.id = rows[index].Id;
				self.form.userName = rows[index].UserName;
				self.form.password = rows[index].Password;
				self.form.firstName = rows[index].FirstName;
				self.form.middleName = rows[index].MiddleName;
				self.form.lastName = rows[index].LastName;
				self.form.email = rows[index].Email;
				self.form.phone = rows[index].Phone;
				self.form.blocked = rows[index].Blocked;
				self.form.deviceId = rows[index].DeviceId;
				self.form.roleId = rows[index].RoleId;
			},
			handleClose(done) {
				this.$refs.form.resetFields();
				this.setDefaultForm();
				done();
			},
			onApply(){
				this.loadingDialog = true;
				if (this.form.id === null)
				{
					this.onAdd();
				}else{
					this.onUpdate();
				}
				this.loadingDialog  = false;
			},
			onAdd(){
				let isValid = 0;
				erp.validate(this, (items) => isValid = items);
				if (isValid) {
					this.dialogVisible = false;
					let self = this;
					$.ajax({
							url: "api/users/add.php",
							data: self.form,
							method: "post"
						}).done(function (data) {
							var json = $.parseJSON(data);
							if(json == 1 | json == 11 || json == 111)
							{
								self.$message({
									type: 'succes',
									message: 'Запись успешно добавлена'
								});
								erp.post("api/users/get.php", null, (items) => self.tableData = items);
							}else{
								self.$message({
									type: 'error',
									message: 'Ошибка при добавлении записи'
								});
							}
						}).fail(function () {
							self.$message({
								type: 'error',
								message: 'Ошибка при добавлении записи'
							});
						}).complete(function () {
							self.loading = false;
						});
				}
			},
			onUpdate(){
				let isValid = 0;
				erp.validate(this, (items) => isValid = items);
				if (isValid) {
					this.dialogVisible = false;
					let self = this;
					$.ajax({
							url: "api/users/update.php",
							data: self.form,
							method: "post"
						}).done(function (data) {
							var json = $.parseJSON(data);
							if(json >= 0)
							{
								self.$message({
									type: 'succes',
									message: 'Запись успешно обновлена'
								});
								erp.post("api/users/get.php", null, (items) => self.tableData = items);
							}else{
								self.$message({
									type: 'error',
									message: 'Ошибка при обновлении записи'
								});
							}
						}).fail(function () {
							self.$message({
								type: 'error',
								message: 'Ошибка при обновлении записи'
							});
						}).complete(function () {
							self.loading = false;
						});
				}
			}
		}
	})
</script>