<?php
?>
<h1><img src="/img/h/h1-deliver-cotagbook-50.png"/>
تحویل اظهارنامه ها به بازبینی</h1>

<style>

#rangeform {
	width:600px;
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
	width:20px;
	margin :3px;
	text-align: center;
}
.text {
	width:150px;
	text-align: center;
}
#year{width:40px;margin :3px;text-align:left;}

</style>

<?php if($this->Count){ ?>
<h4>شماره نامه <?php echo $_POST['mailnum']?>&nbsp;&nbsp;
از تاریخ  <?php echo $_POST['CYear']."/".$_POST["CMonth"]."/".$_POST['CDay'];?>
 الی <?php echo $_POST['FYear']."/".$_POST["FMonth"]."/".$_POST['FDay'];?></h4>
	<?php $this->DeliverList->PresentForPrint();?>
	<form >
		<input type="button" id="printbtn" onclick="window.print()" value='چاپ'/>
	</form>
	
<?php }else{?>

<form id="rangeform" method='post'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>

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
<div>
	<label>شماره نامه</label>
	<input name='mailnum' />
</div>
	
	<a href='/help/#cotag_deliver'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:right;' />
	</a>

<input type='submit' id='sub' value='تحویل' />
</form>
<?php }?>
<script>
		$('#sub').click(function (){
			if(!$('input[name=mailnum]').val())
			{
				alert("شماره نامه نمی تواتند خالی باشد");
				return false;
			}
		});
			
		$('#printbtn').click(function (e) {
			window.print();
			return false;
		});
	
</script>