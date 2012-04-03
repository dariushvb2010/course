<?php
?>
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
#BarcodeContainer{
	text-align:center;
	margin:10px;
}
#BarcodeContainer div{
	text-align:center;
	margin:auto;
	background-color:#fff;
	width:500px;
	border:double;
	padding:20px;
@media print {
	#exceptBarcode{
		display:none;
	}
	
	#datetime.Footer {
	
		display:none !important; 
	}
	#Copyright{
	{
		visibility:hidden;
		display:none !important; 
	}
}
</style>
<div id='exceptBarcode'>
<h1>چاپ بارکد</h1>
<form method='post'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
	<a href='/help/#archive_new'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
	</a>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' id='Cotag' />

</div>
<input id='prints' type='submit' id='sub' value='چاپ' />
</form>
</div>
<?php if(isset($_POST['Cotag'])){?>
<div id="BarcodeContainer" align="center">
	<div>
		<img src='<?php echo jURL::Root();?>/barcode?number=<?php echo  $_POST['Cotag'];?>&width=3&height=100&font=18'>
	</div>
</div>
<?php }?>
<script>

$(document).ready(function(){
	
	$("form input[name='Cotag']").focus();
});
$('#prints').click(function ()
		{
			 window.open("./bar?cotag="+$("form input[name='Cotag']").val());
		});
</script>