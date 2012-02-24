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
	width:500px;
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
#formtable td{text-align:center;}
</style>
<h1><img src="/img/h/h1-send-50.png" />
ارسال اظهار نامه</h1>
 

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
<span style='margin-right: 10px; float:right;'> <input
	type="checkbox" id="SelectAll" /> انتخاب همه </span> 
	
	<?php
	if($this->AssignList)
		$this->AssignList->Present();
	?> 
	<table id="formtable" style="width:100%">
		<tr>
			<td>شماره نامه</td>
			<td><input name='Mailnum' /></td>
		</tr><tr>
			<td> به </td>
			<td>
				<select  name='Requester' style="width:139px;">
					<?php 
							if($this->Options)
							foreach($this->Options as $k=>$v)
							{
								echo "<option value='".$k."'>".$v."</option>";
							} 
					?>
				</select>
			</td>
		</tr><tr>
			<td style="vertical-align:top;">توضیحات:</td>
			<td id="textarea"><textarea name ='comment' rows="4" cols="40"></textarea></td>
		</tr><tr>
			<td colspan="2"><input type="submit" id='itemsubmit' value='ارسال'></td>
		</tr>
	</table>
	</form>
	<?php 
	}
	if (count($this->ResultList)) { //results?>
<div id='rangeResult'>
<h2>اظهار نامه های ارسال شده :</h2>
	<?php
	$this->ResultList->Present();
	?> <a href="./send?to=<?php echo $_GET['to']?>">بازگشت</a></div>
	<?php }?>
	
	
<script type="text/javascript"> const CotagLength=<?php echo CotagLength;?></script>
<script src='/script/addlist.js'></script>