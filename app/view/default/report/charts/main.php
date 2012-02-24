<?php
?>
<script type="text/javascript" src="/script/highcharts/highcharts.js"></script>
<script type="text/javascript" src="/script/highcharts/themes/gray.js"></script>
<script type="text/javascript" src="/script/highcharts/modules/exporting.js"></script>
<script type="text/javascript" src="/script/persianDate.js"></script>
<link rel="stylesheet" href="/style/xxx.css" />

<div class="menuHolder">

<ul class="menu1">

<li><a href="?charttype=0" class="red">دفتر کوتاژ</a></li>

<li><a href="?charttype=1" class="orange">کارشناسی</a></li>

<li><a href="?charttype=2" class="yellow">DEMOS</a></li>

<li><a href="#url" class="green">MENUS</a></li>

<li><a href="#url" class="blue">LAYOUTS</a></li>

<li><a href="#url" class="indigo">CONTACT</a></li>

<li><a href="#url" class="violet">PRIVACY</a></li>

</ul>
</div>
<div class="shadow"></div>
<div style="clear: both;"></div>

<script type="text/javascript" src="/script/chartconfig/<?php echo $this->ConfigFileName;?>.js"></script>
<script type="text/javascript">
<?php 	switch ($this->ChartType){?>
<?php 	case 'daftar_cotag':
?>
			chartconfig['series'][0]['pointStart']= <?php echo $this->firstday;?>;
			chartconfig['series'][0]['data']= [<?php echo implode(',',$this->daily1);?>];
<?php 	break;
	 	case 'percentage':
?>
 			chartconfig['series'][0]['data']= [<?php echo implode(',',$this->percentarray);?>];
<?php
	 	break;
	  	
?>
<?php } ?>
$(document).ready(function() {
		chart = new Highcharts.Chart(chartconfig);	
	});	
</script>
<!-- the container -->
<div id="container" style="width: 800px; height: 400px; margin: 0 auto"></div>