<?php
?>
<script type="text/javascript" src="/script/highcharts/highcharts.js"></script>
<script type="text/javascript" src="/script/highcharts/themes/gray.js"></script>
<script type="text/javascript" src="/script/highcharts/modules/exporting.js"></script>
<script type="text/javascript" src="/script/persianDate.js"></script>
<link rel="stylesheet" href="/style/xxx.css" />
<style>
	#rangeform { width:600px; margin:auto; padding:10px; border:3px double; text-align:center;margin:auto; }
	#mailrange{margin:8px; padding:5px; border:1px solid #ddd; width: auto; display:inline-block;}
	form input[type='submit'] { width:200px; margin:5px; }
	.date{ width:20px; margin :3px;text-align: center; }
	.text { width:150px; text-align: center; }
	#year{width:40px;margin :3px;text-align:left;}
	div#body>div.mainList{border:4px double black; margin: 5px 12px; padding:5px; min-height:200px;}
	table.autolist, table.autolistprint{clear:both}
	<?php
	

	if($this->Handler->SearchForm)
		$this->Handler->SearchForm->PresentCSS();
	if($this->Handler->MainList)
		$this->Handler->MainList->PresentCss();
	?>
</style>

<div class="menuHolder">

<ul class="menu1">

<li><a href="?charttype=0" class="red">آمار کلی کارشناسی بازبینی</a></li>

</ul>
</div>
<div class="shadow"></div>
<div style="clear: both;"></div>
<form  id="mailrange" method='post'>
	<div>
		<label>از تاریخ</label>
		<input class='date' type='text' name='CMin' value='00'/> :
		<input class='date' type='text' name='CHour' value='00'/> &nbsp;&nbsp;&nbsp;&nbsp;
		<input class='date' type='text' name='CDay' value='<?php if (isset($_POST['CDay']))echo $_POST['CDay'];else echo $this->today[2];?>'/> /
		<input class='date' type='text' name='CMonth' value='<?php if (isset($_POST['CMonth']))echo $_POST['CMonth'];else echo $this->today[1];?>' /> /
		<input id='year' type='text' name='CYear' value='<?php if (isset($_POST['CYear']))echo $_POST['CYear'];else echo $this->today[0];?>' /> 
	</div>
	<div>
		<label>تا تاریخ</label>
		<input class='date' type='text' name='FMin' value='00'/> :
		<input class='date' type='text' name='FHour' value='00'/> &nbsp;&nbsp;&nbsp;&nbsp;
		<input class='date' type='text' name='FDay'   value='<?php if (isset($_POST['FDay']))echo $_POST['FDay'];else echo $this->tomorrow[2];?>'/> /
		<input class='date' type='text' name='FMonth' value='<?php if (isset($_POST['FMonth']))echo $_POST['FMonth'];else echo $this->tomorrow[1];?>'/> /
		<input id='year' type='text' name='FYear' value= '<?php if (isset($_POST['FYear']))echo $_POST['FYear'];else echo $this->tomorrow[0];?>' /> 
	</div>
	
	<div align="center"><input type='submit' name="Date" value='مشاهده' /></div>
</form>
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