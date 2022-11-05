<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.ct-series-a .ct-point {
  /* Цвет точек */
  stroke: green;
  /* Размер точек */
  stroke-width: 1px;
  /* Сделать точки квадратами */
  stroke-linecap: square;
}
</style><div id="app">
	<template>
		<div v-loading="loading">
			<canvas id="myChart"></canvas>
		</div>
		<div style="display: flex;">
			<div class="block">
				<span class="demonstration">&nbsp;с</span>
				<el-date-picker
					v-model="form.dateBegin"
					type="datetime"
					placeholder="Select date and time">
				</el-date-picker>
			</div>
			<div class="block">
				<span class="demonstration">&nbsp;&nbsp;по</span>
				<el-date-picker
					v-model="form.dateEnd"
					type="datetime"
					placeholder="Select date and time">
				</el-date-picker>
			</div>
			<div class="block">
				<span class="demonstration">&nbsp;&nbsp;Сенсор</span>
				<el-select v-model="form.sensorId" placeholder="Select" style="width: 150px">
					<el-option
						v-for="item in options"
						:key="item.value"
						:label="item.label"
						:value="item.value">
					</el-option>
				</el-select>
			</div>
			<div class="block">
				<span class="demonstration">&nbsp;&nbsp;Мин</span>
				<el-select v-model="form.scaleMin" placeholder="Select" style="width: 100px" @change="changeScaleMin">
					<el-option
						v-for="item in scale"
						:key="item.value"
						:label="item.label"
						:value="item.value">
					</el-option>
				</el-select>
			</div>
			<div class="block">
				<span class="demonstration">&nbsp;&nbsp;Макс</span>
				<el-select v-model="form.scaleMax" placeholder="Select" style="width: 100px" @change="changeScaleMax">
					<el-option
						v-for="item in scale"
						:key="item.value"
						:label="item.label"
						:value="item.value">
					</el-option>
				</el-select>
			</div>
			&nbsp;
			<el-button type="primary" @click="onRefresh">Обновить</el-button>
		</div>
		<!--<br/>
		
		<el-table
			:data="tableData"
			border
			style="width:100%"
			v-loading="loading">
			<el-table-column
				prop="Id"
				label="№ п/п"
				width="50"
				>
			</el-table-column>
			<el-table-column
				prop="CreationDate"
				label="Дата измерения"
				width="250">
			</el-table-column>
			<el-table-column
				prop="Value"
				label="Показания ДУТа"
				width="250">
			</el-table-column>
		</el-table>
		--->
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
			form:{
					dateBegin: new Date((new Date()).getTime() - 24*60*60*1000),
					dateEnd: new Date(),
					sensorId: 0,
					scaleMin: 0,
					scaleMax: 5000,
			},
			options: [{
				value: 0,
				label: 'DutE S7'
			}, {
				value: 1,
				label: 'Dut'
			}],
			scale: [{
				value: 0,
				label: '0'
			}],
			dialogVisible: false,
			formLabelWidth: '120px',
			tableData: [{}],
			loading: false,
			myChart: new Chart(document.getElementById('myChart'), null ),
			config: {
						type: 'line',
						data: {
								labels: [],
								datasets: [{
									lineTension: 0,
									borderDash: [5, 5],
									label: 'ДУТ',
									backgroundColor: 'rgb(255, 99, 132)',
									borderColor: 'rgb(255, 99, 132)',
									borderRadius:1,
									borderWidth:1, //толщина линий 
									fill: false,
									data: [],
								}]
						},
						options: {
							radius: 2,
							scales: {
										y: {
											suggestedMin: 0,
											suggestedMax: 5000
										}
									}
						}
					},
		},
		created(){
			//this.getTanker();
		},
		mounted: function () {
			this.scale = this.getScale();
			if(typeof window.location != 'undefined'){
				let params = (new URL(document.location)).searchParams;
				console.log(window.location, "window.location")
				console.log(params.get("dateBegin"))
				if(params.get("dateBegin") != null)
				{
					this.form.dateBegin = new Date(Date.parse(params.get("dateBegin")));
					console.log(this.form.dateBegin, "this.form.dateBegin")
				}
				if(params.get("dateEnd") != null)
				{
					this.form.dateEnd = new Date(Date.parse(params.get("dateEnd")));
				}
				if(params.get("min") != null)
				{
					this.config.options.scales.y.suggestedMin = params.get("min");
					this.form.scaleMin = Math.trunc(this.config.options.scales.y.suggestedMin/1000)*1000
				}
				if(params.get("max") != null)
				{
					this.config.options.scales.y.suggestedMax = params.get("max");
					this.form.scaleMax = Math.trunc(this.config.options.scales.y.suggestedMax/1000)*1000
					if(this.form.scaleMax != params.get("max")){
						this.form.scaleMax = this.form.scaleMax + 1000;
					}
				}
				if(params.get("sensorId") != null)
				{
					this.form.sensorId = Number(params.get("sensorId"));
					this.config.data.datasets[0].label = this.getSensorNameById(this.form.sensorId);
				}
			}
			this.getTanker();
			this.$nextTick(function () {
				window.setInterval(() => {
					this.getTanker();
				},100000);
			})
		},
		methods: {
			getSensorNameById(sensorId){
				return this.config.data.datasets[0].label = this.options.filter(f => {
					return f.value === sensorId;
				})[0].label;
			},
			changeScaleMin(){
				this.config.options.scales.y.suggestedMin = this.form.scaleMin;
			},
			changeScaleMax(){
				this.config.options.scales.y.suggestedMax = this.form.scaleMax;
			},
			getScale(){
				var scale = [];
				for (var i = 0; i <= 10; ++i) {
					scale.push({
						value: i * 1000,
						label: i
					});
				}
				return scale;
			},
			dateToTicks(date) {
				const m = date.getMonth() + 1;
				const space = ' ';
				return date.getFullYear() + "-" + m + "-" + date.getDate() + space + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds()
			},
			onRefresh(){
				this.getTanker();
			},
			getTanker(){
				let self = this;
				console.log (this.location)
				if(typeof window.location != 'undefined')
				{
					window.history.pushState(null, null, window.location.pathname + 
					"?page=tanker" + 
					"&dateBegin=" + self.dateToTicks(self.form.dateBegin) + 
					"&dateEnd=" + self.dateToTicks(self.form.dateEnd) + 
					"&min=" + self.config.options.scales.y.suggestedMin + 
					"&max=" + self.config.options.scales.y.suggestedMax + 
					"&sensorId=" + self.form.sensorId);
				}
				this.config.data.datasets[0].label = this.getSensorNameById(this.form.sensorId) + "  (" + self.dateToTicks(self.form.dateBegin) + " - " + self.dateToTicks(self.form.dateEnd) + ")";
				self.loading = true;
				$.ajax({
						url: "api/tanker/get.php",
						data:{
								dateBegin: 	self.dateToTicks(self.form.dateBegin), 
								dateEnd: 	self.dateToTicks(self.form.dateEnd), 
								sensorId: 	self.form.sensorId
							},
						method: "post"
					}).done(function (data) {
						var json = $.parseJSON(data);
						if(json != null && json.length > 0)
						{
							//не начинать график с нуля
							if(json[json.length - 1].Value == null){
								json[json.length - 1].Value = json[json.length - 2].Value;
							}
							var index, len
							for (index = 1, len = json.length; index < len - 1; ++index) {
								//фильтр нулевых значений до 3х минут TODO Refactor this
								if(json[index].Value == null){
									if(json[index - 1].Value > 0 && json[index + 1].Value > 0 ){
										json[index].Value = (Number(json[index - 1].Value) + Number(json[index + 1].Value))/2;
									}
									else{
											if(index < len - 2 && json[index - 1].Value > 0 && json[index + 2].Value > 0 && json[index + 2] != null)
											{
												json[index].Value = (Number(json[index - 1].Value) + Number(json[index + 2].Value))/2;
											}else
											{
												if(index < len - 3 && json[index - 1].Value > 0 && json[index + 3].Value > 0)
												{
													json[index].Value = (Number(json[index - 1].Value) + Number(json[index + 3].Value))/2;
												}
											}
									}
								}
							}
							if(self.config.options.scales.y.suggestedMin > 0){
									for (index = 0, len = json.length; index < len; ++index) {
										if(json[index].Value < self.config.options.scales.y.suggestedMin){
											json[index].Value = self.config.options.scales.y.suggestedMin;
										}
								}
							}
							/*
							if(self.form.sensorId == 1)
							{
								for(var i = 0; i < 10; i++)
									for (index = 1, len = json.length; index < len - 1; ++index) {
										if(json[index].Value != null && json[index + 1].Value != null)
											json[index].Value = (Number(json[index].Value) + Number(json[index + 1].Value))/2;
									}
								if(json[0].Value == null && json.length > 1)
									json[0].Value = json[1].Value;
								
								if(json[json.length-1].Value == null && json.length > 1)
									json[json.length-1].Value = json[json.length-2].Value;
							}*/
							self.tableData = json;
							self.updateGraph();
						}else{
							self.tableData = [{"Id":1,"CreationDate":"2022-01-01 00:00:00"}];
							self.updateGraph();
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
			updateGraph(){
				let self = this;
				self.config.data.datasets[0].data = [];
				self.config.data.labels = [];
				var oldVallue = null;
				self.tableData
					.forEach((element) => {
						if(element.Value == null)
						{
							//element.Value = oldVallue;
						}
						if(element.Value === null)
						{
							self.config.data.datasets[0].data.push(0);
						}
						else
						{
							self.config.data.datasets[0].data.push(Number(element.Value));
						}
						self.config.data.labels.push(element.CreationDate);
						oldVallue = element.Value;
					})
				
				self.config.data.datasets[0].data = self.config.data.datasets[0].data.reverse();
				self.config.data.labels = self.config.data.labels.reverse();
				this.myChart.destroy();
				this.myChart = new Chart(document.getElementById('myChart'), self.config );
			}

		}
	})
</script>