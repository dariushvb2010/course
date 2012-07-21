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
<li><a href="?charttype=1" class="orange">نتایج کارشناسی</a></li>
<li><a href="?charttype=2" class="yellow">حجم کاری کارشناسان</a></li>
<li><a href="?charttype=3" class="green">سرعت کار بازبینی</a></li>
<li><a href="?charttype=4" class="blue">ورودی خروجی</a></li>
<li><a href="?charttype=5" class="teal">حذف فرآیندها</a></li>
<li><a href="?charttype=6" style="background:teal">مبلغ اختلاف</a></li>
</ul>
</div>

<div class="shadow"></div>
<?php 

?>
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
<?php 	break;
	 	case 'karshenas_work_volume':
?>
 			chartconfig['xAxis']['categories']= [<?php echo implode(',',$this->names);?>];
 			chartconfig['series'][0]['data']= [<?php echo implode(',',$this->values);?>];
<?php 	break;
	 	case 'bazbini_speed':
?>
 			
 			chartconfig['xAxis']['categories']= [<?php echo implode(',',$this->X);?>];
 			chartconfig['series'][0]['data']= [<?php echo implode(',',$this->values);?>];
 			chartconfig['series'][1]['data']= [<?php echo implode(',',$this->values);?>];
<?php
	 	break;
	 	case 'in_vs_out':
?>	
 	 		chartconfig['xAxis']['categories']= [<?php echo implode(',',$this->X);?>];
 	 		chartconfig['series'][0]['data']= [<?php echo implode(',',$this->in);?>];
 	 		chartconfig['series'][1]['data']= [<?php echo implode(',',$this->out);?>];
<?php 
 	 	 break;
	 	case 'progress_remove':
?>	
 	 		chartconfig['xAxis']['categories']= [<?php echo implode(',',$this->X);?>];
 	 		chartconfig['series'][0]['data']= [<?php echo implode(',',$this->removes);?>];
<?php 
 	 	break;
 	 	case 'review_amount':
 	 		?>	
 	 		 chartconfig['xAxis']['categories']= [<?php echo implode(',',$this->X);?>];
 	 		 chartconfig['series'][0]['data']= [<?php echo implode(',',$this->stempty);?>];
 	 		chartconfig['series'][1]['data']= [<?php echo implode(',',$this->st109);?>];
 	 		chartconfig['series'][2]['data']= [<?php echo implode(',',$this->st248);?>];
 	 		chartconfig['series'][3]['data']= [<?php echo implode(',',$this->st528);?>];

 	 		//-----for making total statistics ( pie chart) 
 	 		chartconfig['series'][4]['data'][0]['y']= <?php echo $this->totempty;?>;
 	 		chartconfig['series'][4]['data'][1]['y']= <?php echo $this->tot109;?>;
 	 		chartconfig['series'][4]['data'][2]['y']= <?php echo $this->tot248;?>;
 	 		chartconfig['series'][4]['data'][3]['y']= <?php echo $this->tot528;?>;
<?php } ?>
$(document).ready(function() {
		chart = new Highcharts.Chart(chartconfig);	
	});	
</script>
<!-- the container -->
<div id="container" style="width: 800px; height: 400px; margin: 0 auto"></div>