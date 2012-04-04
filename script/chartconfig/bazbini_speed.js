var chartconfig;
chartconfig={
	chart: {
		renderTo: 'container',
		defaultSeriesType: 'areaspline'
	},
	title: {
		text: 'سرعت ورود و کارشناسی اظهارنامه به بازبینی'
	},
	legend: {
		layout: 'vertical',
		align: 'left',
		verticalAlign: 'top',
		x: 150,
		y: 100,
		floating: true,
		borderWidth: 1,
		backgroundColor: '#FFFFFF'
	},
	xAxis: {
		type: 'datetime',
        labels: {
        	rotation: -45,
        	y:20
        },
		plotBands: [{ // visualize the weekend
			from: 4.5,
			to: 6.5,
			color: 'rgba(68, 170, 213, .2)'
		}]
	},
	yAxis: {
		title: {
			text: 'تعداد اظهارنامه'
		}
	},
	tooltip: {
		formatter: function() {
	            return ''+
				this.x +': '+ this.y +' units';
		}
	},
	credits: {
		enabled: false
	},
	plotOptions: {
		areaspline: {
			fillOpacity: 0.5
		}
	},
	series: [{
		name: 'دریافت دفتر کوتاژ',
		type: 'area',
		pointInterval: 30 * 24 * 3600 * 1000,
		pointStart: 0,
		data: [5,5,5,5,5,5,5]
	}, {
		name: 'بازبینی اظهارنامه ها',
		type: 'area',
		pointInterval: 30 * 24 * 3600 * 1000,
		pointStart: 0,
		data: [5,5,5,5,5,5,5]
	}]
};
