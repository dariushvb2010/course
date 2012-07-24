

			var chartconfig;
			
				chartconfig = {
					chart: {
						renderTo: 'container',
						defaultSeriesType: 'line'
					},
					title: {
						text: 'تعداد حذف فرآیند انجام شده در هر ماه'
					},
					subtitle: {
						text: ''
					},
					xAxis: {
						categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
					},
					yAxis: {
						title: {
							text: 'تعداد'
						}
					},
					tooltip: {
						formatter: function() {
							return '<b>'+ this.series.name +'</b><br/>'+
								this.x +': '+ this.y ;
						}
					},
					
					series: [{
						name: 'حذف فرآیند',
						data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
					}]
				};
				