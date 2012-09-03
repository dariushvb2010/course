<style>
#fileinfo{
	font-weight:bold;
	padding:4px;
	margin:5px;
	margin-right:50px;
	margin-left:50px;
	border:1px solid blue;
	height:120px;
}
#fileinfo p{
	margin:0;
	height:30px;
	width:50%;
}
#fileinfo p.left{
	float:left;
}
#fileinfo p.right{
	float:right;
}
</style>
	<div id="fileinfo">
		<p class="left">وضعیت: <?php echo $this->File->State();?></p>
		<p class="right">شماره کوتاژ: <?php echo v::Filecuc($this->File,"Cb,Sb,link");?></p>
		<?php //echo v::bbc($this->File->Cotag());?>
		<p class="left">سریال اظهارنامه: <?php echo ($this->File->BarSerial()?$this->File->BarSerial():'-');?></p>
		<p class="right">کد گمرک: <?php echo $this->File->GateCode();?></p>
		<p class="right">تاریخ وصول: <span dir=ltr><?php echo $this->File->CreateTime();?></span></p>
		<p class="left">شماره کلاسه: <span dir=ltr><?php echo ($this->File->GetClass()?$this->File->GetClass():'-');?></span></p>
		<p class="right">سال: <span dir=ltr><?php echo ($this->File->RegYear()?$this->File->RegYear():'-');?></span></p>
		<p class="left"><?php echo FsmGraph::$StateFeatures[$this->File->State()]['Desc']?></p>
	</div>