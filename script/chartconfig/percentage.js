var chartconfig;
chartconfig={
	chart: {
		renderTo: 'container',
		plotBackgroundColor: null,
		plotBorderWidth: null,
		plotShadow: false
	},
	title: {
		text: 'شیر بازار مصرف آهو کباب در شمیرانات'
	},
	tooltip: {
		formatter: function() {
			return '<b>'+ this.point.name +'</b>: '+ Math.floor(this.percentage*100)/100 +' %';
		}
	},
	plotOptions: {
		pie: {
			allowPointSelect: true,
			cursor: 'pointer',
			dataLabels: {
				enabled: true,
				color: '#000000',
				connectorColor: '#000000',
				formatter: function() {
					return '<b>'+ this.point.name +'</b> : '+ Math.floor(this.percentage*100)/100 +'%';
				}
			}
		}
	},
    series: [{
		type: 'pie',
		name: 'Browser share',
		data: [
			['Firefox',   45.0],
			['IE',       26.8],
			{
				name: 'Chrome',    
				y: 12.8,
				sliced: true,
				selected: true
			},
			['Safari',    8.5],
			['Opera',     6.2],
			['Others',   0.7]
		]
	}]
};