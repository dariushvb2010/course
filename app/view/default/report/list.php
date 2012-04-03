<?php
$mapArray=$this->mapArray;
?>

<style>
form{
	width:80%px;
	margin:auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
form#cotag{
	width:500px;
	margin:auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
form#cotag input[type='submit'] {
	width:200px;
	margin:5px;
}
form#cotag input[type='text'] {
	width:150px;
}
</style>

<h1>لیست کوتاژها</h1>
<p>
تعداد کل :
<strong><?php echo $this->Count;?></strong> 
 </p>
 <form id='cotag' method='post'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
	<a href='/help/#archive_new'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
	</a>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />

</div>

<input type='submit' value='نمایش' />
<br/>
</form>
 <form method='get'>
نمایش از
<input type='text' name='Offset' size='5' value='<?php 
$newOffset=$this->Offset;
echo $newOffset;
?>' />
به تعداد
<input type='text' name='Limit' value='<?php echo $this->Limit;?>' size='5'/>
مرتب سازی بر اساس
<select name='Sort'>
<?php 
foreach ($mapArray as $k=>$v)
{
	if ($this->Sort==$k)
		$sel=" selected='selected' ";
	else 
		$sel="";
	echo "<option {$sel} value='{$k}'>$v</option>\n";
}
?>
</select>
<select name='SortOrder'>
<option value='ASC'  >صعودی</option>
<option value='DESC' <?php if ($this->SortOrder=='DESC') echo " selected='selected' ";?>>نزولی</option>

</select>

<input type='submit' value='برو' />
</form>
<?php 
$positive="<img src='/img/icon/tick16.png' />";
$negative="<img src='/img/web/icon/wrong16.png' />";
if (is_array($this->Data))
{
	?>
	<table class="autolist" border='1' width='100%' cellpadding='2' cellspacing='0'>
	<thead>
	<tr>
	<?php 
	foreach ($mapArray as $m)
	{
		echo "<th>{$m}</th>\n";
	}
	?>
	</tr>
	</thead>
	<?php 
	$c=0;
foreach ($this->Data as $D)
{
	$jc=new CalendarPlugin();
	$O=array();
	$c++;
	foreach ($mapArray as $k=>$v)
	{
		if ($k=='ID')
			$O[$k]=$c;
		elseif ($k=='Cotag')
			$O[$k]=htmlspecialchars($D->Cotag());
		elseif ($k=="CreateTimestamp")
		{
			if ($D->CreateTime())
				$O[$k]=$D->CreateTime();
			else 
				$O[$k]="-";
		}
		elseif ($k=="FinishTimestamp")
		{
			if ($D->FinishTimestamp())
				$O[$k]=$D->FinishTime();
			else 
				$O[$k]="-";
		}
		elseif ($k=="LastProgress")
		{
			$P=$D->Progress();
			if (Count($P)!=0)
				$O[$k]=$P[0]->Title();
			else
				$O[$k]=$negative;	
		}
		
	}
	?>
	<tr valign='top' align='center'>
	<?php
	foreach ($mapArray as $k=>$m)
	{
		if (strpos($k,"Timestamp")!==false)
			$Direction=" dir='ltr' ";
		else 	
			$Direction="";
		echo "<td{$Direction}>{$O[$k]}</td>\n";
	}
	?>	
	
	</tr>	
	<?php 
}
?>
	</table>
	<?php 
}
?>
