<?php
class ViewTransferPlugin extends JPlugin
{
static function Present(BaseViewClass $View, $Title="ارسال اظهارنامه ها")
{
	$H=$View->Handler;
	if(!$H)
	{
		echo "شما اجازه دسترسی به این قسمت را ندارید.";
		return;
	}
	if($H instanceof HandleTransferSingle or $H instanceof HandleTransferSearch)
	{
		$HomeLink=ViewMailPlugin::HomeLink($H->Action(), $H->Source(), $H->Dest());
	}
	if(!($H instanceof HandleTransferSearch or $H instanceof HandleTransferSingle))
	{
		$SLink=FPlugin::getAddress();
		if(strpos($SLink,"?")!==false)
			$SLink.="&Search=yes";
		else
			$SLink.="?Search=yes";
	}
	?>
	
	<style>
	input[type=submit]{min-width:200px;}
	div#body>div.mainform{border:4px double black; margin: 5px 12px; padding:5px;}
	<?php
	
	if($H->CreateForm)
			$H->CreateForm->PresentCSS();
	if($H->SearchForm)
		$H->SearchForm->PresentCSS();
	if($H->MainForm);
	//$H->MainForm->PresentCSS();
	
	?>
	</style>
	<?php ViewMailPlugin::EchoCSS();?>
	<h1>
		<?php echo $Title;
			if($HomeLink): ?>
				<a href="<?php echo $HomeLink; ?>"> <img style="height: 30px; float:left; margin:7px 7px 0 12px;" src="/img/mail/back.png" title="برگشت به صفحه اصلی نامه ها"/></a>
			<?php endif;
			if($SLink):
			?>
			<a href="<?php echo $SLink; ?>" style="float:left; margin:7px 7px 0 12px" ><img src="/img/search-32.png" title="جستجوی نامه ها"/></a>
			<?php endif;?>
	</h1>
	<?php
	ViewResultPlugin::Show($H->Result, $H->Error);
	ViewResultPlugin::Show($View->Result, $View->Error);
	if($H->CreateForm)
		$H->CreateForm->PresentHTML();
	?>
	<!-- --------------------------main form of the mail -->
	<?php if($H->MainForm):?>
			<div class="mainform" ><?php 
			ViewMailPlugin::SingleShow($H->Mail, "float:left;",$H->Action());
			$H->MainForm->PresentHTML();
			?>
			</div>
	<?php endif; 
		if($H->SearchForm)
			$H->SearchForm->PresentHTML();
		if($H->EditForm)
		{
			echo "<div style='font-size:large; text-decoration:underline; font-weight:bold; clear:both;'>ویرایش مشخصات نامه </div>";
			$H->EditForm->PresentHTML();
		}
	 	$H->ShowMails();
	 	?>
	 	
	 	
	 	
	 	
	<script>
	<?php 
	if($H->MainForm)
		$H->MainForm->PresentScript();
	if($H->Action()=="Get"):
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