<?php
?>
<h1><img src="/img/h/h1-print-barcode-50.png"/>
چاپ بارکد های چاپ نشده</h1>
<style>

#rangeform {
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
.date
{
	width:25px;
	margin :3px;
	text-align: center;
}
.text {
	width:150px;
	text-align: center;
}
#year
{
	width:40px;
	margin :3px;
	text-align:left;
}
</style>

<br/>

<form id="rangeform" method='post'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>

<div>
	<label>از تاریخ</label>
	<input class='date' type='text' name='CDay' value='<?php if (isset($_POST['CDay']))echo $_POST['CDay'];else echo $this->today[2];?>'/> /
	<input class='date' type='text' name='CMonth' value='<?php if (isset($_POST['CMonth']))echo $_POST['CMonth'];else echo $this->today[1];?>' /> /
	<input id='year' type='text' name='CYear' value='<?php if (isset($_POST['CYear']))echo $_POST['CYear'];else echo $this->today[0];?>' /> 
</div>
<div>
	<label>تا تاریخ</label>
	<input class='date' type='text' name='FDay'   value='<?php if (isset($_POST['FDay']))echo $_POST['FDay'];else echo $this->today[2]+1;?>'/> /
	<input class='date' type='text' name='FMonth' value='<?php if (isset($_POST['FMonth']))echo $_POST['FMonth'];else echo $this->today[1];?> '/> /
	<input id='year' type='text' name='FYear' value= '<?php if (isset($_POST['FYear']))echo $_POST['FYear'];else echo $this->today[0];?>' /> 
</div>
	<a href='/help/#cotag_deliver'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:right;' />
	</a>

<input type='submit' value='چاپ' />
</form>
<script>
	function print()
	{
		$('#printbtn').click(fucntion (e) {
			window.print();
			return false;
		});
	}
</script>