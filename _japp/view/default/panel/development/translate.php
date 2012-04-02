<?php 
if ($this->EditResult)
	echo "Saved.".BR;
?>
<style>
tr td input,textarea {
	border:0px;
	overflow:auto;
}

</style>
<?php
if ($this->Phrases)
{
?><table border='1' cellspacing='0' width='95%'>
<thead>
<tr>
<th width='50'>Language</th>
<th width='300'>Phrase</th>
<th width='300'>Translation</th>
<th width='50'>Operation</th>
</tr>
</thead>
<?php 
	foreach ($this->Phrases as $p)
	{
	?>
	<tr><form method='post'>
	<td align='center'><input readonly="readonly" size='7' name='Language' value='<?php echo $p['Language']?>'/></td>	
	<?php if (strlen($p['Phrase'])<80)
	{
	?>
	<td><input size='60' readonly="readonly" name='Phrase' value="<?php echo htmlspecialchars($p['Phrase'])?>" /></td>	
	<td><input size='70' name='Translation' value='<?php echo htmlspecialchars($p['Translation'])?>' /></td>
	<?php } else {?>
	<td><textarea cols='60' rows='5' readonly="readonly" name='Phrase' ><?php echo htmlspecialchars($p['Phrase'])?></textarea></td>	
	<td><textarea cols='70' rows='5' name='Translation' ><?php echo htmlspecialchars($p['Translation'])?></textarea></td>
	<?php }?>	
<td align='center'>
	<input type='submit' name='Operation' value='Save' />
	<input type='submit' name='Operation' value='Delete' />
	</td>
	</form>
	</tr>
	<?php	
	}
?>
</table>
<?php 	
}
?>
<hr/>
<form method='get'>
	Starting from <input name='off' value='<?php echo $_GET['off']+$_GET['lim']?>' />
	Show <input name='lim' value='<?php echo $_GET['lim']?$_GET['lim']:50?>' /> items
	<input type='submit' />
</form>