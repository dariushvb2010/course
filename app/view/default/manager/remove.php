<?php
?>
<h1>حذف آخرین فرایند یک اظهارنامه یا پرونده </h1>
<!--<h5>حذف آخرین فرایند یک اظهارنامه یا پرونده آن را یک مرحله به عقب بر می گرداند.</h5>-->
<style>

form {
	width:500px;
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
</style>

<form method='post'>
<?php
$jc = new CalendarPlugin();
 if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
	<a href='/help/#archive_new'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
	</a>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />

</div>
<input type='submit' id='sub' value='حذف آخرین فرایند' />
</form>
<?php if(isset ($_POST['Cotag']) && !isset($_POST['confirm'])){?>
<form method="post">
<h3> آخرین فرایند کوتاژ <?php echo $_POST['Cotag'];?>به شرح زیر می باشد:</h3>
<?php if($this->LLP){?>
<div class="progress"><span style='color:blue;'>
			 <?php echo $this->LLP->Title();?>:</span>
			<span class="summaryinfo"><?php echo $this->LLP->Summary();?><br></span>
			<span class="footinfo"> توسط <?php echo $this->LLP->User()->getFullName();?> در تاریخ: <span dir=ltr><?php echo $jc->JalaliFullTime($this->LLP->CreateTimestamp(),"/");?></span></span>
</div>
	<div>
		<label>  توضیحات حذف:</label>
	<textarea name='Comment' ></textarea>
	</div>
<input type="submit" value='تایید حذف' name='confirm' />	
<input type="hidden" name="Cotag" value='<?php echo $_POST['Cotag'];?>'/>	
<?php }else {?>
<h5 style="color: red">هیچ فرایند قابل حذفی وجود ندارد.</h5>
<?php }?>
</form>
<?php }?>
<script>
	
	$("form input[name='Cotag']").focus();

</script>