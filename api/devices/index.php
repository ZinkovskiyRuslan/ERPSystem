<div id="app">
	<template>
		<el-button type="text" @click="dialogVisible = true; dialogTitle = 'Добавить новое устройство'; ">
			<i class="fa fa-mobile" aria-hidden="true"></i>
			Добавить новое устройство
		</el-button>
		<el-dialog
			:title="dialogTitle"
			:visible.sync="dialogVisible"
			:before-close="handleClose">
				<el-form :model="form" ref="form" v-loading="loadingDialog">
					<el-form-item
						label="Id устройства"
						:label-width='formLabelWidth'
						:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
						prop="device"
					>
						<el-input v-model="form.device" v-mask="'XXXXXXXXXXXXXXXX'" autocomplete="off"></el-input>
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
				width="90">
				<template slot-scope="scope">
					<el-button style="margin-left: 10px;"
						@click.native.prevent="editRow(scope.$index, tableData)"
						type="text"
						size="small">
							<i class="fa fa-pencil" aria-hidden="true"></i>
					</el-button>
					<el-button 
						@click.native.prevent="deleteRow(scope.$index, tableData)"
						type="text"
						size="small">
							<i class="fa fa-trash" style = "color: red;" aria-hidden="true"></i>
					</el-button>
				</template>
			</el-table-column>
			<el-table-column
				prop="Device"
				label="Id устройства"
				width="250">
			</el-table-column>
			<el-table-column
				prop="CreationDate"
				label="Дата записи"
				width="250">
			</el-table-column>
			<el-table-column
				prop="LastName"
				label="Фамилия"
				width="250">
			</el-table-column>
			<el-table-column
				prop="FirstName"
				label="Имя"
				width="250">
			</el-table-column>
			<el-table-column
				prop="MiddleName"
				label="Отчество"
				width="250">
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
				device: null
			},
			defaultForm: null,
		},
		created(){
			this.defaultForm = Object.assign({}, this.form);
			this.get();
		},
		methods: {
			setDefaultForm()
			{
				this.form = Object.assign({}, this.defaultForm);
				this.$refs.form.resetFields();
			},
			get(){
				let self = this;
				self.loading = true;
				$.ajax({
						url: "api/devices/get.php",
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
			},
			tableRowClassName({row, rowIndex}){
				if (row.Blocked === 1){
					return 'blocked-row';
				}
				return '';
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
						url: "api/devices/remove.php",
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
			editRow(index, rows){
				let self = this;
				self.dialogVisible = true;
				self.dialogTitle = "Редактирование Id устройства";
				self.form.id = rows[index].Id;
				self.form.device = rows[index].Device;
			},
			handleClose(done) {
				this.$confirm(
						'Закрыть форму?', 
						'Внимание', 
						{confirmButtonText: 'Да',cancelButtonText: 'Нет',type: 'warning',}
					)
				.then(_ => {
					this.$refs.form.resetFields();
					this.setDefaultForm();
					done();
				})
				.catch(_ => {});
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
							url: "api/devices/add.php",
							data: self.form,
							method: "post"
						}).done(function (data) {
							var json = $.parseJSON(data);
							if(json == true)
							{
								self.$message({
									type: 'succes',
									message: 'Запись успешно добавлена'
								});
								erp.post("api/devices/get.php", null, (items) => self.tableData = items);
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
							url: "api/devices/update.php",
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
								erp.post("api/devices/get.php", null, (items) => self.tableData = items);
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