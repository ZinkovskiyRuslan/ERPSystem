<div id="app">
	<template>
	<!--
		<el-button type="text" @click="dialogVisible = true;">
			<i class="fa fa-plus" aria-hidden="true"></i>
			Добавить новую запись
		</el-button>
	-->
		<el-dialog
			title="Добавить новую запись"
			:visible.sync="dialogVisible"
			width="30%"
			:before-close="handleClose">
				<el-form :model="form" ref="form" v-loading="loadingDialog">
					<el-form-item
						label="Новое значение"
						:label-width='formLabelWidth'
						:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
						prop="valueTo"
					>
						<el-input v-model="form.valueTo" autocomplete="off"></el-input>
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
				</template>
			</el-table-column>
			<el-table-column
				prop="Sensor"
				label="Датчик"
				width="250">
			</el-table-column>
			<el-table-column
				prop="ValueCurrent"
				label="Текущее значение"
				width="250">
			</el-table-column>
			<el-table-column
				prop="ValueTo"
				label="Новое значение"
				width="250">
			</el-table-column>
			<el-table-column
				prop="SetDate"
				label="Дата установки нового значения"
				width="250">
			</el-table-column>
			<el-table-column
				prop="UpdateDate"
				label="Дата связи с датчиком"
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
			formLabelWidth: '120px',
			tableData: [{}],
			loading: false,
			loadingDialog: false,
			form: {
				id: null,
				valueTo: null,
			},
			defaultForm: null,
		},
		created(){
			this.defaultForm = Object.assign({}, this.form);
			this.getfuelinformations();
		},
		methods: {
			setDefaultForm()
			{
				this.form = Object.assign({}, this.defaultForm);
				this.$refs.form.resetFields();
			},
			getfuelinformations(){
				let self = this;
				self.loading = true;
				$.ajax({
						url: "api/incubator/get.php",
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
						url: "api/fuelInformations/remove.php",
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
				self.form.id = rows[index].Id;
				self.form.valueTo = rows[index].ValueTo;
				console.log(self.form);
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
				this.onUpdate();
				this.loadingDialog  = false;
			},
			onUpdate(){
				let isValid = 0;
				erp.validate(this, (items) => isValid = items);
				if (isValid) {
					this.dialogVisible = false;
					let self = this;
					$.ajax({
							url: "api/incubator/update.php",
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
								erp.post("api/incubator/get.php", null, (items) => self.tableData = items);
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