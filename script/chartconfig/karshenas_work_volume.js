var chartconfig;
chartconfig={
		chart: {
			renderTo: 'container',
			//defaultSeriesType: 'bar'
			type:'column'
		},
		title: {
			text: 'حجم کاری کارشناسان در 30 روز گذشته'
		},
		xAxis: {
			labels: {
				//rotation: -45,
	        	//y:50
			},
			categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
		},
		yAxis: {
			min: 0,
			title: {
				text: 'تعداد اظهارنامه'
			}
		},
		legend: {
			backgroundColor: '#FFFFFF',
			reversed: true
		},
		tooltip: {
			formatter: function() {
				return ''+
					 this.series.name +': '+ this.y +'';
			}
		},
		plotOptions: {
			series: {
				stacking: 'normal'
			}
		},
	    series: [{
	    	name:'تعداد',
			data: [5, 3, 4, 7, 2]
		}]

};