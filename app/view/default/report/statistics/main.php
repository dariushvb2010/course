<?php
?>
<script type="text/javascript" src="/script/highcharts/highcharts.js"></script>
<script type="text/javascript" src="/script/highcharts/themes/gray.js"></script>
<script type="text/javascript" src="/script/highcharts/modules/exporting.js"></script>
<script type="text/javascript" src="/script/persianDate.js"></script>
<link rel="stylesheet" href="/style/xxx.css" />

<div class="menuHolder">

<ul class="menu1">

<li><a href="?charttype=0" class="red">آمار کلی کارشناسی بازبینی</a></li>

<li><a href="?charttype=1" class="orange">نتایج کارشناسی</a></li>

<li><a href="?charttype=2" class="yellow">حجم کاری کارشناسان</a></li>

<li><a href="?charttype=2" class="green">سرعت کار بازبینی</a></li>

</ul>
</div>
<div class="shadow"></div>
<div style="clear: both;"></div>
<?php 	if($this->ChartType=='numberResults'){
	if(isset($this->AutoListResult))
	{
		echo "<h2>آمار خطایابی توسط کارشناسان</h2>";
		$this->AutoListResult->Present();
	}
	if(isset($this->AutoListProvision))
	{
		echo "<h2>آمار شماره کلاسه اعلامی توسط کارشناسان</h2>";
		$this->AutoListProvision->PresentForPrint();
	}
	if(isset($this->AutoList1))
	{
		echo "<h2> اطلاعات کارشناسی درب خروج</h2>";
		$this->AutoList1->PresentForPrint();
	}
	if(isset($this->AutoList2))
	{
		echo "<h2> اطلاعات پرداخت های درب خروج</h2>";
		$this->AutoList2->PresentForPrint();
	}
} ?>
<!-- the container -->
<div id="container" style="width: 800px; height: 400px; margin: 0 auto"></div>