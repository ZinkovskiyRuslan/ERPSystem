
<script src="js/print.js" rel="stylesheet"></script>

<html><head><title>documentTitle</title></html>  
<div id="app">
	<template>
		<el-button type="text" @click="dialogVisible = true; form.fillType = 0; dialogTitle = 'Добавить в АЗС'; ">
			<i class="fa-solid fa-gas-pump"></i>
			Добавить в АЗС
		</el-button>
		<el-button type="text" @click="dialogVisible = true; form.fillType = 2; dialogTitle = 'Добавить в журнал';">
			<i class="fa-solid fa-list-ol"></i>
			Добавить в журнал
		</el-button>
		<el-button type="text" @click="print();">
			<i class="fa-solid fa-print"></i>
			Печать
		</el-button>
		<el-dialog
			:title="dialogTitle"
			:visible.sync="dialogVisible"
			:close-on-click-modal="false"
			:before-close="handleClose">
				<el-form :model="form" ref="form" v-loading="loadingDialog">
					<el-form-item v-if = "form.fillType != 0" 
						label="Из справочника" 
						:label-width="formLabelWidth"
						:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
						prop="isResident"
					>
						<el-switch v-model="form.isResident">
					</el-form-item>
					<template v-if="form.isResident">
						<el-form-item 
							label="Водитель" 
							:label-width="formLabelWidth"
							:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
							prop="userId"
						>
							<el-select v-model="form.userId" placeholder="Укажите водителя">
								<el-option
									v-for="item in drivers"
									:key="item.value"
									:label="item.label"
									:value="item.value">
								</el-option>
							</el-select>
						</el-form-item>
						<el-form-item 
							label="Гос. Номер" 
							:label-width="formLabelWidth"
							:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
							prop="carId"
						>
							<el-select v-model="form.carId" placeholder="Укажите номер транспортного средства">
								<el-option
									v-for="item in cars"
									:key="item.value"
									:label="item.label"
									:value="item.value">
								</el-option>
							</el-select>
						</el-form-item>
					</template>
					<template v-else>
						<el-form-item 
							label="Водитель" 
							:label-width="formLabelWidth"
							prop="driver"
						>
							<el-input v-model="form.driver" autocomplete="off"></el-input>
						</el-form-item>
						<el-form-item 
							label="Гос. Номер" 
							:label-width="formLabelWidth"
							prop="number"
						>
							<el-input v-model="form.number" autocomplete="off"></el-input>
						</el-form-item>
					</template>
					<el-form-item
						label="Выдать топливо"
						:label-width='formLabelWidth'
						:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
						prop="fuel"
						v-if = "form.fillType == 0"
					>
						<el-input v-model="form.fuel" autocomplete="off"></el-input>
					</el-form-item>
					<el-form-item
						label="Выдано"
						:label-width='formLabelWidth'
						:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
						prop="fuelFill"
						v-if = "form.fillType == 2"
					>
						<el-input v-model="form.fuelFill" autocomplete="off"></el-input>
					</el-form-item>
					<el-form-item
						label="Дата выдачи"
						:label-width='formLabelWidth'
						:rules="[{ required: true, message: 'Обязательно для заполнения', trigger: 'blur' }]"
						prop="fuelFillDate"
						v-if = "form.fillType == 2"
					>
					<el-date-picker
							v-model="form.fuelFillDate"
							type="date"
							placeholder="Дата выдачи">
						</el-date-picker>
					</el-form-item>
				</el-form>
				<span slot="footer" class="dialog-footer">
					<el-button @click="dialogVisible = false; setDefaultForm();">Отмена</el-button>
					<el-button type="primary" @click="onApply">Сохранить</el-button>
				</span>
		</el-dialog>

		<div id="filter">
			<div style="display: flex;">
				<div>
					<el-date-picker
						v-model="filter.dateBegin"
						type="date"
						placeholder="Дата начала"
						style="width: 150px">
					</el-date-picker>
				</div>
				<div>
					&nbsp;-
					<el-date-picker
						v-model="filter.dateEnd"
						type="date"
						placeholder="Дата окончания"
						style="width: 150px">
					</el-date-picker>
				</div>
				&nbsp;
				<el-button type="primary" @click="onRefresh">Обновить</el-button>
			</div>
		</div>
		
		<br/>
		
		<el-table
			:data="tableData"
			id='printRegion'
			border
			style="width: 100%"
			show-summary
			:summary-method="getSummaries"
			v-loading="loading"
			>
			<el-table-column
				prop="Id"
				label="№ п/п"
				width="70"
				>
				<template slot-scope="scope">
					{{scope.row.Id}}
					<i v-if="scope.row.FillType == 0 && scope.row.Closed == 0" class="fa-solid fa-gas-pump" v-bind:style="{color:'#409EFF'}"></i>
					<i v-if="scope.row.FillType == 0 && scope.row.Closed == 1" class="fa-solid fa-gas-pump" v-bind:style="{color:'gray'}"></i>
					<i v-if="scope.row.FillType == 1" class="fa-solid fa-toggle-off" v-bind:style="{color:'gray'}"></i>
					<i v-if="scope.row.FillType == 2" class="fa-solid fa-list-ol" v-bind:style="{color:'gray'}"></i>
				</template>
			</el-table-column>
			<el-table-column
				v-if="isShowAction"
				prop="Id"
				label="Действия"
				width="90">
				<template slot-scope="scope">
					<el-button style="margin-left: 10px;"
						@click.native.prevent="editRow(scope.$index, tableData)"
						type="text"
						size="small">
							<i class="fa fa-pencil" v-bind:style="{color: scope.row.Blocked && scope.row.FillType == 0 ? 'gray' : '#409EFF;'}" aria-hidden="true"></i>
					</el-button>
					<el-button 
						@click.native.prevent="deleteRow(scope.$index, tableData)"
						type="text"
						size="small">
							<i class="fa fa-trash" v-bind:style="{color: scope.row.Blocked ? 'gray' : 'red'}" aria-hidden="true"></i>
					</el-button>
				</template>
			</el-table-column>
			<el-table-column
				prop="FIO"
				label="ФИО Водителя"
				width="250">
			</el-table-column>
			<el-table-column
				prop="CarNumber"
				label="Гос. номер"
				width="140">
			</el-table-column>
			<el-table-column
				prop="number"
				label="Объём топлива в баке"
				width="140">
			</el-table-column>
			<el-table-column
				prop="Fuel"
				label="Выдать топливо"
				width="140">
			</el-table-column>
			<el-table-column
				prop="FuelFill"
				label="Выдано"
				width="140">
			</el-table-column>
			<el-table-column
				prop="FuelFillDate"
				label="Дата выдачи"
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
			isShowAction: true,
			formLabelWidth: '120px',
			tableData: [],
			sumLabels: ['Выдать топливо', 'Выдано'],
			loading: false,
			loadingDialog: false,
			filter:{
				dateBegin: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()),
				dateEnd: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()),
			},
			form: {
				id: null,
				userId: null,
				carId: null,
				fuel: null,
				fuelFill: null,
				fuelFillDate:Date.now(),
				fuelFillDateSql: null,
				//0-заправка по приложению
				//1-заправка по кнопке
				//2-ввод из журнала
				fillType:null,
				isResident:1,
				driver:null,
				number:null
			},
			drivers: [],
			cars: [],
			defaultForm: null,
		},
		created(){
			this.defaultForm = Object.assign({}, this.form);
			this.get(isShowWarning = false);
		},
		methods: {
			setDefaultForm()
			{
				this.form = Object.assign({}, this.defaultForm);
				this.$refs.form.resetFields();
			},
			get(isShowWarning = true){
				let self = this;
				self.loading = true;
				$.ajax({
						url: "api/fuelInformations/get.php",
						data:{
							dateBegin: 	erp.dateToSqlFormat(self.filter.dateBegin), 
							dateEnd: 	erp.dateToSqlFormat(self.filter.dateEnd)
						},
						method: "post"
					}).done(function (data) {
						if(data != "null")
						{
							self.tableData = $.parseJSON(data);
						}else{
							self.tableData = [];
							if(isShowWarning)
							{
								self.$message({
									type: 'warning',
									message: 'Список пуст',
								});
							}
						}
					}).fail(function () {
						self.$message({
							type: 'error',
							message: 'Ошибка получения данных'
						});
					}).complete(function () {
						self.loading = false;
					});
				erp.post("api/shared/select/getDrivers.php", null, (items) => self.drivers = items);
				erp.post("api/shared/select/getCars.php", null, (items) => self.cars = items);
			},
			getSummaries(param) {
			const { columns, data } = param;
			const sums = [];
			columns.forEach((column, index) => {
				if (index === 0) {
					sums[index] = 'Итого:';
					return;
				}
				if(!this.sumLabels.includes(column.label))
				{
					sums[index] = ' ';
					return;
				}
				const values = data.map(item => Number(item[column.property]));
				if (!values.every(value => isNaN(value))) {
				sums[index] = values.reduce((prev, curr) => {
					const value = Number(curr);
					if (!isNaN(value)) {
						return prev + curr;
					} else {
						return prev;
					}
				}, 0);
				} else {
					sums[index] = ' ';
				}
			});

			return sums;
			},
			tableRowClassName({row, rowIndex}){
				if (row.Fuel <=  row.FuelFill){
					return '';// 'blocked-row';
				}
				return '';
			},
			deleteRow(index, rows) {
				if(rows[index].Blocked)
					return -1;	
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
				if(rows[index].Blocked && rows[index].FillType == 0)
					return -1;
				let self = this;
				self.dialogVisible = true;
				self.form.id = rows[index].Id;
				self.form.userId = rows[index].UserId;
				self.form.carId = rows[index].CarId;
				self.form.fuel = rows[index].Fuel;
				self.form.fuelFill = rows[index].FuelFill;
				self.form.fuelFillDate = rows[index].FuelFillDate;
				self.form.fillType = rows[index].FillType;
				self.form.isResident = rows[index].IsResident == 0 ? false : true;
				self.form.driver = rows[index].Driver;
				self.form.number = rows[index].Number;
				console.log("self.form", self.form);
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
							url: "api/fuelInformations/add.php",
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
								self.get(isShowWarning = false);
							}else{
								if(json == -2)
								{
									self.$message({
										type: 'error',
										message: 'Уже существуюет доступная заправка'
									});
								}else{
									self.$message({
										type: 'error',
										message: 'Ошибка при добавлении записи'
									});
								}
							}
						}).fail(function () {
							self.$message({
								type: 'error',
								message: 'Ошибка при добавлении записи'
							});
						}).complete(function () {
							self.loading = false;
						});
					this.setDefaultForm();
				}
				
			},
			onUpdate(){
				let isValid = 0;
				erp.validate(this, (items) => isValid = items);
				if (isValid) {
					this.dialogVisible = false;
					let self = this;
					if(self.form.fuelFillDate != null && self.form.fillType != 0)
					{
						if(typeof self.form.getMonth === 'function')
						{
							self.form.fuelFillDateSql = erp.dateToSqlFormat(self.form.fuelFillDate);
						}else{
							self.form.fuelFillDateSql = self.form.fuelFillDate;
						}
					}
					$.ajax({
							url: "api/fuelInformations/update.php",
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
								self.get(isShowWarning = false);
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
					this.setDefaultForm();
				}
				
			},
			onRefresh(){
				this.get();
			},
			print(){
				this.isShowAction = false;
				const documentTitle = erp.dateToString(this.filter.dateBegin) + " - " + erp.dateToString(this.filter.dateEnd);
				this.$nextTick(() => {printJS('printRegion', 'html', documentTitle);this.isShowAction = true;})
			}
		}
	})
</script>
<style>
.el-dialog {
	width: fit-content;
	padding: 0px 50px;
}
</style>