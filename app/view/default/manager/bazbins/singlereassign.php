<?php
?>
<style>
form{
	margin:auto;
	width:500px;
	border:double;
	padding:20px
}
#singleResult {
	width:400px;
	border:3px double gray;
	color:darkgreen;
	font-size:36px;
	font-weight:bold;
	margin:10px auto;
	padding:10px;
	text-align:center;
}
h3{
	margin-bottom:0; padding-bottom:0;
}
#singleResult span {
	font-size:12px;
}
<?php $this->Form->PresentCSS(); ?>
<?php $this->AddList->PresentCSS(); ?>
</style>
<h1><img src="/img/h/h1-cancelassign-50.png"/>
مدیریت تخصیص</h1>
<p>وارد کردن توضیحات الزامی است.</p>
<!-- ----------------------------------------cancel assign------------- -->
<h3>لغو تخصیص اظهارنامه</h3>
<?php if (isset($this->Result) && isset($_POST['Cancel']))
ViewResultPlugin::Show($this->Result,$this->Error);
?>
<form method='post' >
<label for='Cotag'>کوتاژ</label><input disabled="disabled" name="Cotag"/><br/>
<label for="Comment">توضیحات</label><textarea disabled="disabled" name="Comment" value=""><?php echo $this->Comment;?></textarea><br/>
<input type='submit' disabled="disabled" name="Cancel" value="لغو تخصیص" />
</form>
<br/>
<!-- ----------------------------------------reassign----------------- -->
<h3>تخصیص مجدد اظهارنامه</h3>
<?php if (isset($_POST['Reassign']))
ViewResultPlugin::Show($this->Result,$this->Error);
?>
<?php $this->Form->PresentHTML(); ?>
<script>
<?php $this->Form->PresentScript();?>
</script>
<?php if ($this->Reviewer){	?>
<div id='singleResult'>
<span style='float:left;'>شماره کوتاژ :‌ 
<strong>
<?php echo $this->Cotag;?>
</strong>
</span>
<span>کارشناس : </span>
<?php
	echo $this->Reviewer->Firstname()," ",$this->Reviewer->Lastname();?>
</div>
<?php 
}
?>
<script type="text/javascript">
<?php $this->AddList->PresentScript();?>
$('#CommentBox').click(function(){
		$("#Comment").attr("value",$("#CommentBox option:selected").text());
	});
$("div.autoform :text[name=Cotag]").keydown(function(event){
	  if(event.keyCode==13)
		  DList.AddRow();
	});
</script>