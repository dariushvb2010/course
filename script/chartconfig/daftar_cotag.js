var chartconfig;
chartconfig={
	chart: {
		renderTo: 'container',
		zoomType: 'x',
		spacingRight: 20
	},
    title: {
		text: 'فعالیت روزانه دفتر کوتاژ'
	},
    subtitle: {
		text: document.ontouchstart === undefined ?
			'با موس قسمتی از نمودار را برای یزرگ نمایی انتخاب کنید' :
			'Drag your finger over the plot to zoom in'
	},
	xAxis: {
		type: 'datetime',
        labels: {
        	rotation: -45,
        	y:20,
        	formatter: function() {
                var gd = new Date(this.value);
                var pa = calcGregorian(gd.getFullYear(),gd.getMonth() +1, gd.getDate());
                return PERSIAN_WEEKDAYS[pa[3]]+''+pa[2];
            }
        },
		maxZoom: 14 * 24 * 3600000, // fourteen days
		title: {
			text: null
		}
	},
	yAxis: {
		title: {
			text: 'تعداد اظهارنامه دریافتی'
		},
		min: 0.6,
		startOnTick: false,
		showFirstLabel: false
	},
	tooltip: {
		shared: false,			
	},
	legend: {
		enabled: false
	},
	plotOptions: {
		area: {
			fillColor: {
				linearGradient: [0, 0, 0, 300],
				stops: [
					[0, Highcharts.getOptions().colors[0]],
					[1, 'rgba(2,0,0,0)']
				]
			},
			lineWidth: 1,
			marker: {
				enabled: false,
				states: {
					hover: {
						enabled: true,
						radius: 5
					}
				}
			},
			shadow: false,
			states: {
				hover: {
					lineWidth: 1						
				}
			}
		}
	},
	series: [{
		type: 'area',
		name: 'تعداد اظهارنامه',
		pointInterval: 24 * 3600 * 1000,
		pointStart: 0,
		data: [5,5,5,5,5,5,5]
	}]
};