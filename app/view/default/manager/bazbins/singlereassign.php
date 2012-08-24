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
<?php $this->CancelForm->PresentCSS(); ?>
<?php $this->CancelList->PresentCSS(); ?>
</style>
<h1><img src="/img/h/h1-cancelassign-50.png"/>
مدیریت تخصیص</h1>
<p>وارد کردن توضیحات الزامی است.</p>
<!-- ***********************cancel assign*********************** -->
<h3>لغو تخصیص اظهارنامه</h3>
<?php if (isset($_POST['CancelAssign']))
ViewResultPlugin::Show($this->CancelResult,$this->CancelError);
$this->CancelForm->presentHTML();
?>

<br/>
<!-- ***********************reassign*********************** -->
<h3>تخصیص مجدد اظهارنامه</h3>
<?php if (isset($_POST['Reassign']))
ViewResultPlugin::Show($this->ReassignResult,$this->ReassignError);
?>
<?php $this->ReassignForm->PresentHTML(); ?>



<script>
<?php $this->CancelList->PresentScript();?>
<?php $this->ReassignList->PresentScript();?>
$('#CommentBox').click(function(){
		$("#Comment").attr("value",$("#CommentBox option:selected").text());
	});

</script>