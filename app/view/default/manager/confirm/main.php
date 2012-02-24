<?php
?>
<style>

form {
	width:80%;
	margin:auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
.info{
	width:80%;
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
	text-align:center;
}
.autoform div.even{
		background:rgb(128,190,232);
	}
	.autoform div.odd{
		background:rgb(236,245,253);
	}
	.autoform{
		background:rgb(236,245,253);
	}
<?php $this->Form->PresentCSS();?>
</style>
<h1><img src="/img/h/h1-confirm-50.png" />
تایید نظر کارشناس بازبینی</h1>
<p>

</p>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<a href='./select'>انتخاب اظهارنامه بعدی</a>
<div id="ReviewInfo" class='info'>
	<p>شماره کوتاژ:<?php echo $this->Cotag;?></p>
	<p>
	<?php echo $this->lastreview->Summary();?>
	</p>
	<p>کارشناس:<?php echo $this->lastreview->User()->getFullName();?></p>
</div>
<?php if(!empty($this->lastprogress)){?>
	<div id="ManagerConfirm" class='info'>
		<p>
		<?php echo $this->lastprogress->Summary();?>
		</p>
		<p>توسط مسئول بازبینی:<?php echo $this->lastprogress->User()->getFullName();?></p>
	</div>
<?php } ?>
<?php if (!$this->Result) { ?>
<?php $this->Form->PresentHTML();?>
<script>
<?php $this->Form->PresentScript();?>
</script>
<?php }
else
{?>
<?php }
?>