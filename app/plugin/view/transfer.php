<?php
class ViewTransferPlugin extends JPlugin
{
static function Present(BaseViewClass $View, $Title="ارسال اظهارنامه ها")
{
	?>
	
	<style>
	div#body>div.mainform{border:4px double black; margin: 5px 12px; padding:5px;}
	<?php
	if($View->Handler->CreateForm)
			$View->Handler->CreateForm->PresentCSS();
	if($View->Handler->SearchForm)
		$View->Handler->SearchForm->PresentCSS();
	if($View->Handler->MainForm);
	//$View->Handler->MainForm->PresentCSS();
	
	?>
	</style>
	<?php ViewMailPlugin::EchoCSS();?>
	<h1>
	<?php echo $Title;?>
	</h1>
	<?php
	ViewResultPlugin::Show($View->Handler->Result, $View->Handler->Error);
	if($View->Handler->CreateForm)
		$View->Handler->CreateForm->PresentHTML();
	?>
	<!-- --------------------------main form of the mail -->
	<?php if($View->Handler->MainForm):?>
			<div class="mainform" ><?php 
			ViewMailPlugin::SingleShow($View->Handler->Mail, "float:left;","Give");
			$View->Handler->MainForm->PresentHTML();
			?>
			</div>
	<?php endif; 
		if($View->Handler->SearchForm)
			$View->Handler->SearchForm->PresentHTML();
		if($View->Handler->EditForm)
		{
			echo "<div style='font-size:large; text-decoration:underline; font-weight:bold; clear:both;'>ویرایش مشخصات نامه </div>";
			$View->Handler->EditForm->PresentHTML();
		}
	 	$View->Handler->ShowMails();
	 	?>
	<script>
	<?php 
	if($View->Handler->MainForm)
		$View->Handler->MainForm->PresentScript();
	
	?>
	</script>
	<?php 
}
}