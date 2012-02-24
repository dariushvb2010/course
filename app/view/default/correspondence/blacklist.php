<?php
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
#filelist{
	margin:5px;
}
</style>

<h1>لیست مشمولین ماده ۱۴ و ۱۵</h1>
<p>
تعداد پرونده های مشمول ماده ی ۱۴ و ۱۵ : 
<strong> 
<?php echo $this->Count; ?> 
 </strong>
</p>

<?php if($this->Count){ ?>
	<div id="filelist">
		<?php
			$this->FileAutoList->Present(); 
		?>
	</div>
<?php }else{?>
<?php }?>
<script>
$(function(){
	
	$("form input[name='Cotag']").focus();
});
</script>