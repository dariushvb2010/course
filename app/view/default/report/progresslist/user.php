<?php
	$jc=new CalendarPlugin();
?>
<style>

form {
	width:400px;
	margin:auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
form input[type='submit'] {
	width:200px;
	margin:5px;
}
form input[type='text'] {
	width:150px;
}
#progresses{
	padding:4px;
	margin:5px;
	margin-right:50px;
	margin-left:50px;
	border:1px solid blue;
}
.progress{
	padding:5px;
}
span.summaryinfo{
	font-size:17px;
}
span.footinfo{
	font-size:12px;
}
#fileinfo{
	font-weight:bold;
	padding:4px;
	margin:5px;
	margin-right:50px;
	margin-left:50px;
	border:1px solid blue;
}
</style>

<h1><img src="/img/h/h1-workflow2-50.png" />
فعالیت های ثبت شده توسط شما</h1>
<?php 
if (is_array($this->Data)){?>
	<div id="fileinfo">
		<p>نام: <?php echo $this->User->getFullName();?></p>
		<p>تعداد: <?php echo $this->Count;?></p>
	</div>
	<?php if(count($this->Data)==0){?>
	هیچ گزارشی موجود نیست 
	<?php }else{ ?>
	<div id="progresses">
	<?php $i=$this->Count; foreach ($this->Data as $D){
		?>
		<div class="progress"><span style='color:blue;'>
			<?php echo $i;?>- <?php echo $D->Title()."(".$D->File()->Cotag().")"; ?>:</span>
			<span class="summaryinfo"><?php echo $D->Summary();?><br></span>
			<span class="footinfo"> توسط <?php echo $D->User()->getFullName();?> در تاریخ: <span dir=ltr><?php echo $jc->JalaliFullTime($D->CreateTimestamp(),"/");?></span></span>
		</div> 
	<?php $i--;}?>
	</div>
	<?php } ?>
<?php } ?>
