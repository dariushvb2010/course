<?php if($this->File):?>
<style>
#fileinfo{
	font-weight:bold;
	padding:4px;
	margin:5px;
	margin-right:50px;
	margin-left:50px;
	border:1px solid blue;
}
#fileinfo table{
	width:100%;
}
#fileinfo img#toggle-asy{
	margin-top:-25px;
	width:30px;
	float:left;
	cursor:pointer;
}
#fileinfo table.autolistprint{
	display:none;
	margin-left:7px;
	width:auto;
	min-width:60%;
}
</style>
	<div id="fileinfo">
		<table>
		<tr>
			<td>شماره کوتاژ: <?php echo v::Filecuc($this->File,"Cb,Sb,link");?></td>
			<td>وضعیت: <?php echo $this->File->State();?></td>
		</tr><tr>
			<td>کد گمرک: <?php echo $this->File->GateCode();?></td>
			<td>سریال اظهارنامه: <?php echo ($this->File->BarSerial()?$this->File->BarSerial():'-');?></td>
		</tr><tr>
			<td>شماره کلاسه: <span dir=ltr><?php echo v::bec(($this->File->Classe()?$this->File->Classe():'-'));?></span></td>
			<td>تاریخ وصول: <span dir=ltr><?php echo $this->File->CreateTime();?></span></td>
		</tr><tr>
			<td>سال: <span dir=ltr><?php echo ($this->File->RegYear()?$this->File->RegYear():'-');?></span></td>
			<td><?php echo FsmGraph::$StateFeatures[$this->File->State()]['Desc']?></td>
		</tr>
		</table>
		<img id="toggle-asy" src="/img/arrow2down-50.png" />
		<?php
		$this->Asy = $this->File->Asy(); 
		if($this->Asy)
		include 'asydatabox.php';?>
	</div>
	<script>
		(function t(){
			$('img#toggle-asy').click(function(){
				im = $(this);
				asy = $('#fileinfo table.autolistprint');
				if(asy.is(':visible')){
					asy.hide('blind');
					//im.attr('src','/img/arrow2up-50.png');
				}
				else{
					asy.show('blind');
					//im.attr('src','/img/arrow2up-50.png');
				}
			});
		})();
	</script>
	
<?php endif;?>