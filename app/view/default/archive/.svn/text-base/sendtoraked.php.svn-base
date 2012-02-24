<?php
?>
<style>
#sortform_Select {
}

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
#sortform_Select{
	width:80% !important;
}
#singleResult span {
	font-size: 12px;
}

#rangeResult .Reviewer {
	color: darkgreen;
	font-weight: bolder;
}
</style>
<h1><img src="/img/h/h1-send-50.png" />

فرستادن اظهارنامه به بایگانی راکد</h1>
<p>
<strong>توجه : </strong>
تنها پرونده‌هایی که روند کاری آنها کامل شده است، یعنی توسط کارشناس بازبینی بدون مشکل تشخیص داده شده قابل فرستادن به بایگانی راکد هستند.
</p>
<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
if (isset($this->Mails))
		foreach($this->Mails as $m)
		{
			echo "<a href='?ID=".$m->ID()."' >".$m->Num()." "."</a><br/>";
		}
 if(!isset($_GET['MailNum']) && !isset($_GET['ID'])){?>
 
<form>
<label>شماره نامه </label><input name="MailNum"/>
<br/>
<label>توضیحات</label><textarea rows="3" cols="10"></textarea>
<br/>
<input type='submit' value="ایجاد" id="sub" />
</form>
<?php }else{?>
<?php if (count($this->Finishable)) {
	//$this->List->PresentSortbox();

	?>
	<form>
		<label>کوتاژ</label><input id='cotag'><br>
		<input type="button" id='addButton' value = 'اضافه'>
	</form>
	<?php 		
	?> 
<form method='post'>
<span style='margin-right: 10px; float: right;'> <input
	type="checkbox" id="SelectAll"> انتخاب همه </span> 
	<?php
	$this->List->Present();
	?> <input type="submit" name='send' value='ارسال'/>
		<input type="submit" name='save' value='ذخیره' disabled="disabled"/>
	</form>
	<?php }

	if (count($this->ResultList)) { //results?>
<div id='rangeResult'>
<h2>نتایج فرستادن به بایگانی راکد</h2>
	<?php
	$this->ResultList->PresentForPrint();
	?></div>
	<?php }}?>
<script>
	$("#SelectAll").click(function()
			{
				$(".item").attr("checked",$("#SelectAll").attr("checked"));
			});

	var a=0;
	function IsNumeric(input){
	    var RE = /^-{0,1}\d*\.{0,1}\d+$/;
	    return (RE.test(input));
	}
	function trim(str)
	{
	        return str.replace(/^\s+|\s+$/g,"");
	}
	function trimZero(str)
	{
		return str.replace(/^0+/, '');
	}
	function addRow()
	{
		var cotag=$('#cotag').val();
		cotag=trimZero(cotag);
		cotag=trim(cotag);
		if(!IsNumeric(cotag) || parseInt(cotag)<1 || cotag.length!=<?php echo CotagLength?>)
		{
			alert("کوتاژ ناصحیح است ");
			return false;
		}
		
		$("#list").show(200);
//		if(a>50)
//		{
//			alert("حداکثر 50 کوتاز می توان تخصیص داد؛");
//			return false;
//		}
		a++;
		var flag =true;
		$.each($("td"),function (i,n){
			if(($(this).text())==(cotag))
				{
					flag=false;
					$(this).prev().children(':first-child').attr("checked","checked");
				}
		});
		if(flag)
			alert("کوتاژ موجود نیست.");
		
		$('#cotag').val('');
		return false;
	}
	
	$("#SelectAll").click(function()
			{
				$(".item").attr("checked",$("#SelectAll").attr("checked"));
			});
	
	$("#addButton").click(function()
			{
				addRow();
			}
		);
			
	$("#cotag").keydown(function(event){
		  if(event.keyCode==13)
		  {
			   addRow();
		  		return false;
		  }
		});
	$('#sub').click(function (){
		if(!$('input[name=MailNum]').val())
		{
			alert("شماره نامه نمی تواتند خالی باشد");
			return false;
		}
	});
	
	
</script>
