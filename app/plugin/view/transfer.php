<?php
class ViewTransferPlugin extends JPlugin
{
static function Present(BaseViewClass $View, $Title="ارسال اظهارنامه ها")
{
	if(!$View->Handler)
	{
		echo "شما اجازه دسترسی به این قسمت را ندارید.";
		return;
	}
	?>
	
	<style>
	input[type=submit]{min-width:200px;}
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
	ViewResultPlugin::Show($View->Result, $View->Error);
	
	if($View->Handler->CreateForm)
		$View->Handler->CreateForm->PresentHTML();
	?>
	<!-- --------------------------main form of the mail -->
	<?php if($View->Handler->MainForm):?>
			<div class="mainform" ><?php 
			ViewMailPlugin::SingleShow($View->Handler->Mail, "float:left;",$View->Handler->Action);
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
	if($View->Handler->Action=="Get"):
	?>
	function Select(Cotag)
	{
		res=false;
		td=$("table.autolist td[Header='Cotag']");
		$.each(td,function(i,n){
			MyCotag=$(this).html();
			if(MyCotag==Cotag)
			{
				sibs=$(this).siblings("td[header=Select]");
				check=sibs.children("input");
				check.attr('checked','checked');
				res=true;				
			}
		});
		return res;
	}
	function ShowResult(res)
	{
		if(res==true)
			text="<span style='color:green;'>انتخاب شد</span>";
		else
			text="<span style='color:red;'>در لیست وجود ندارد</span>";
			
		span=$(":text[name=SelectCotag]").parent("div").find("span#Result");
		if(!span.length)
		{
			$(":text[name=SelectCotag]").parent("div").append("<span id='Result'>hi</span>");
		}
		$(":text[name=SelectCotag]").parent("div").find("span#Result").html(text);
		
	}
	$(":text[name=SelectCotag]").keydown(function(event){
	    if(event.keyCode == 13){
	        $("button[name=Select]").click();
	    }
	});
	$("button[name=Select]").click(function(){
		Cotag=$(":text[name=SelectCotag]").val();
		Cotag=trimZero(Cotag);
		Cotag=trim(Cotag);
		ShowResult(Select(Cotag));
		$(":text[name=SelectCotag]").val("");
	});
	
	$("input#selectall[type=checkbox]").click(function()
	{
		$("input.item[type=checkbox]").attr("checked",$("#selectall").attr("checked"));
	}); 
	
	function trim(str)
	{
	        return str.replace(/^\s+|\s+$/g,"");
	}
	function trimZero(str)
	{
		return str.replace(/^0+/, '');
	}
		
	<?php endif;?>
	</script>
	<?php 
}
}