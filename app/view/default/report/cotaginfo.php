<?php
?>
<h1><img src="/img/h/h1-cotaginfo-50.png"/>
اطلاعات کوتاژ و درب خروج </h1>
<style>
form {
	width:60%;
	margin:auto;
	margin-bottom:10px;
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

</style>

<form method='post'>
	<a href='/help/#archive_new'>
		<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
	</a>
	<div>
		<label>شماره کوتاژ</label>
		<input type='text' name='Cotag' value="<?php echo $_POST['Cotag'];?>"/>
	</div>
	<input type='submit' value='نمایش' />
</form>

<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);

if(count($_POST))
{
	if(isset($this->AutoList))
	{
		echo "<h2> 	اطلاعات کوتاژ </h2>";
		$this->AutoList->Present();
	}
	if(isset($this->AutoListCom))
	{
		echo "<h2> 	اطلاعات کالا </h2>";
		$this->AutoListCom->PresentForPrint();
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
 }
 ?>
<script>
$(function(){
	
	$("form input[name='Cotag']").focus();
});
</script>