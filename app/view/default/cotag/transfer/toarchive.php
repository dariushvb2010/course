<?php

	if(!$this->Handler)
	{
		echo "شما اجازه دسترسی به این قسمت را ندارید.";
		return;
	}
	?>
	<style>
	#rangeform { width:600px; margin:auto; padding:10px; border:3px double; text-align:center; }
	#mailrange{margin:8px; padding:5px; border:1px solid #ddd; width: auto; display:inline-block;}
	form input[type='submit'] { width:200px; margin:5px; }
	.date{ width:20px; margin :3px;text-align: center; }
	.text { width:150px; text-align: center; }
	#year{width:40px;margin :3px;text-align:left;}
	div#body>div.mainList{border:4px double black; margin: 5px 12px; padding:5px; min-height:200px;}
	table.autolist, table.autolistprint{clear:both}
	<?php
	

	if($this->Handler->SearchForm)
		$this->Handler->SearchForm->PresentCSS();
	if($this->Handler->MainList)
		$this->Handler->MainList->PresentCss();
	?>
	</style>
	<?php ViewMailPlugin::EchoCSS();?>
	<h1><img src="/img/h/h1-deliver-cotagbook-50.png"/>
	تحویل اظهارنامه ها به بازبینی</h1>
	<?php
	ViewResultPlugin::Show($this->Handler->Result, $this->Handler->Error);
	if($this->Handler instanceof HandleTransferCotagbookPublic):
	?>
	
<form id="rangeform" method='post'>
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
		<input name='Num' />
	</div>
	<div><label>عنوان نامه</label><input name="Subject"/></div>
	<div><label> توضیحات</label><input name="Description"/></div>
		<a href='/help/#cotag_deliver'>
		<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:right;' />
		</a>
	
	<input type='submit' name="Create" id='sub' value='ایجاد نامه' />
</form>
<?php endif;?>
	<!-- ***********************main form of the mail*********************** -->
<?php if($this->Handler->MainList):?>
	<div class="mainList" >
		<?php ViewMailPlugin::SingleShow($this->Handler->Mail, "float:left;","Give"); 
			if($this->Handler->Mail->State()==Mail::STATE_EDITING):?>
			<form  id="mailrange" method='post'>
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
				
				<div align="center"><input type='submit' name="Date" id='sub' value=' مشاهده کوتاژها' /></div>
				<input type='hidden' name="MailID" value="<?php echo $this->Handler->Mail->ID();?>"/>
			</form><?php 
			else:
				if($this->Handler->Mail) echo "تاریخ تحویل: "."<b>".$this->Handler->Mail->GiveTime()."</b>";
			endif;
		$this->Handler->MainList->PresentForPrint();
		?>
	</div>
<?php endif; 
		if($this->Handler->SearchForm)
			$this->Handler->SearchForm->PresentHTML();
		if($this->Handler->EditForm)
		{
			echo "<div style='font-size:large; text-decoration:underline; font-weight:bold; clear:both;'>ویرایش مشخصات نامه </div>";
			$this->Handler->EditForm->PresentHTML();
		}
	 	$this->Handler->ShowMails();
	 	?>
	<script>
	<?php 
	if($this->Handler->MainList)
		$this->Handler->MainList->PresentScript();
	
	?>
	</script>
	<?php 

