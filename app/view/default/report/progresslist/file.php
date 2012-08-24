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
</style>

<h1><img src="/img/h/h1-workflow2-50.png" />
گردش کار اظهارنامه</h1>

<?php if (is_array($this->Data)){?>
	<?php include '/../blocks/fileinfobox.php';?>
			
	<div id="progresses">
	<?php $i=0; foreach ($this->Data as $D){ $i++;?>
		<div class="progress">
		
			<span style='color:<?php echo ($D->Dead()?'orange':'blue'); ?>;'>
				<?php echo $i;?>- <?php echo $D->Title()." ( ".$D->PrevState()." ) ";?>:
			</span>
			<span class="summaryinfo"><?php echo $D->Summary();?><br></span>
				<span class="footinfo">
				 	توسط <?php echo $D->User()->getFullName();?> در تاریخ: <span dir=ltr><?php echo $jc->JalaliFullTime($D->CreateTimestamp(),"/");?>
				</span>
			</span>
			
		</div> 
	<?php }?>
	</div>
	
<?php } ?>

<form method='post'>
<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
?>
<p>
شماره کوتاژ اظهارنامه‌ را وارد نمایید.
</p>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' value='<?php echo $this->Cotag;?>'/>

</div>

<input type='submit' value='انتخاب' />
</form>
