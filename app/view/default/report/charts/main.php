<?php
?>
<script type="text/javascript" src="/script/highcharts/highcharts.js"></script>
<script type="text/javascript" src="/script/highcharts/themes/gray.js"></script>
<script type="text/javascript" src="/script/highcharts/modules/exporting.js"></script>
<script type="text/javascript" src="/script/persianDate.js"></script>
<link rel="stylesheet" href="/style/xxx.css" />

<!-- 2. Add the JavaScript to initialize the chart on document ready -->
<script type="text/javascript">

Vertical1242022 = false;

ShowAdHereBanner1242022 = true;

RepeatAll1242022 = false;

NoFollowAll1242022 = false;

BannerStyles1242022 = new Array(

    "a{display:block;font-size:11px;color:#888;font-family:verdana,sans-serif;margin:0;text-align:center;text-decoration:none;overflow:hidden; float:left;}",

    "img{border:0;}",

    "a.adhere{color:#666;font-weight:bold;font-size:12px;background:#f8f8f8;text-align:center;}",

    "a.adhere:hover{background:#ddd;color:#333;}"
);

</script>

<div class="menuHolder">

<ul class="menu1">

<li><a href="#url" class="red">PRODUCTS</a></li>

<li><a href="#url" class="orange">SERVICES</a></li>

<li><a href="#url" class="yellow">DEMOS</a></li>

<li><a href="#url" class="green">MENUS</a></li>

<li><a href="#url" class="blue">LAYOUTS</a></li>

<li><a href="#url" class="indigo">CONTACT</a></li>

<li><a href="#url" class="violet">PRIVACY</a></li>

</ul>
</div>
<div class="shadow"></div>
<div style="clear: both;"></div>

		<script type="text/javascript">
		
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'container',
						zoomType: 'x',
						spacingRight: 20
					},
				    title: {
						text: 'USD to EUR exchange rate from 2006 through 2008'
					},
				    subtitle: {
						text: document.ontouchstart === undefined ?
							'Click and drag in the plot area to zoom in' :
							'Drag your finger over the plot to zoom in'
					},
					xAxis: {
						type: 'datetime',
				        labels: {
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
							text: 'Exchange rate'
						},
						min: 0.6,
						startOnTick: false,
						showFirstLabel: false
					},
					tooltip: {
						shared: true					
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
						name: 'USD to EUR',
						pointInterval: 24 * 3600 * 1000,
						pointStart: <?php echo $this->firstday;?>,
						data: [<?php echo implode(',',$this->daily1);?>]
					}]
				});
				
				
			});
				
		</script>
<!-- 3. Add the container -->
	<div id="container" style="width: 800px; height: 400px; margin: 0 auto"></div>
				
	</body>
</html>
