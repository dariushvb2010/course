<?php
?>
<style>
#sortform_Select {
}

#singleResult {
	width: 400px;
	border: 3px double gray;
	color: darkgreen;
	font-size: 36px;
	font-weight: bold;
	margin: 10px auto;
	padding: 10px;
	text-align: center;
}

form , #box{
	width:600px;
	margin:auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
input[type='submit'] {
	width:200px;
	margin:5px;
}
input[type='text'] {
	width:150px;
}

#singleResult span {
	font-size: 12px;
}

#rangeResult .Reviewer {
	color: darkgreen;
	font-weight: bolder;
}

</style>
<h1><img src="/img/h/h1-assign-group-50.png"/>
تعیین کارشناس کوتاژ های متفرقه</h1>
<p class="noprint">همه ی کوتاژ های انتخابی در فرم زیر پس از زدن دکمه تخصیص به کارشناسی واحد اختصاص می یابند</p> 
<div id='box'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
if(!count($_POST['item'])){
	?>
		<a href='/help/#archive_new'>
		<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
		</a>
	<div>
		<label>شماره کوتاژ</label>
		<input type='text' name='Cotag' id="cotag"/>
	</div>
	
	<input type='button' id="addButton" value='اضافه کردن' />
</div>
	
	
	
	<form method='post' id='list'>
		<span style='margin-right: 10px; float:right;'>
		 <input type="checkbox" id="SelectAll"/> انتخاب همه </span> 
		<?php
		$this->AssignList->Present();
		?> <input type="submit" id="itemsubmit" style='width:250px;' value='تخصیص کوتاژ های انتخابی به کارشناس' />
	</form>
		<?php 
}
if (count($this->ResultList) && count($this->ResultList->Data)) { //results?>
	<div id='rangeResult'>
		<h3>اظهار نامه های تخصیص شده :</h3>
		<p style="margin:0">کارشناس:
		<b><?php echo $this->ReviewerName;?></b></p>
		<p style="margin:0">تاریخ تخصیص:
		<b dir='ltr'><?php echo $this->AssignDate;?></b></p>
		<?php
		$this->ResultList->PresentForPrint();
		?><a class='noprint' href="./group">بازگشت</a>
	</div>
<?php }?>
<script type="text/javascript"> const CotagLength=<?php echo CotagLength;?></script>
<script src='/script/addlist.js'></script>