chartconfig = {
					chart: {
						renderTo: 'container',
						defaultSeriesType: 'column'
					},
					title: {
						text: 'مبلغ اختلاف گرفته شده به تفکیک کلاسه بر حسب میلیون ریال'
					},
					xAxis: {
						categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
					},
					yAxis: {
						min: 0,
						title: {
							text: 'مبلغ اختلاف بر حسب میلیون ریال'
						},
						stackLabels: {
							enabled: true,
							style: {
								fontWeight: 'bold',
								color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
							}
						}
					},
					legend: {
						align: 'right',
						x: -100,
						verticalAlign: 'top',
						y: 20,
						floating: true,
						backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
						borderColor: '#CCC',
						borderWidth: 1,
						shadow: true
					},
					tooltip: {
						formatter: function() {
							//console.log(this);
						//----------edited by dariush--------
							if(this.x==undefined)
							{
								return '<b>'+ this.point.config.name +'</b><br/>'+
								 this.series.name +': '+ this.y +'<br/>'+
								 'اختلاف کلی: '+ Math.round(this.point.total*10)/10 ;
							}
							else
							{
								return '<b>'+ this.x +'</b><br/>'+
									 this.series.name +': '+ this.y +'<br/>'+
									 'کلی: '+ Math.round(this.point.stackTotal*10)/10 ;
							}
						}//-------------------------------------
					},
					plotOptions: {
						column: {
							stacking: 'normal',
							dataLabels: {
								enabled: false,
								color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
							}
						}
					},
				    series: [{
						name: 'خالی',
						data: [5, 3, 4, 7, 2]
					}, {
						name: '۱۰۹',
						data: [2, 2, 3, 2, 1]
					}, {
						name: '۲۴۸',
						data: [3, 4, 4, 2, 5]
					}, {
						name: '۵۲۸',
						data: [3, 4, 4, 2, 5]
					}, {
						type: 'pie',
						
						name: 'اختلاف ',
						data: [{
							name: 'خالی',
							y: 13,
							color: 'yellow' // yellow
						}, {
							name: '۱۰۹',
							y: 23,
							color: '#4572A7' // blue
						}, {
							name: '۲۴۸',
							y: 19,
							color: '#89A54E' // green
						}, {
							name: '۵۲۸',
							y: 20,
							color: '#AA4643' // red 
						}],
						center: [40, 30],
						size: 70,
						showInLegend: false,
						dataLabels: {
							enabled: false
						}
					}]
				};
				