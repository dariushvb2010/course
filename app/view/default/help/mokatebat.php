<link rel="stylesheet" href="/style/help.css"  />
<style>
img#fsm.zoom{
	margin:-20px -200px -200px;
	position:relative
	z-index:9999999999;
	width:1600px;
}
img#fsm.normal{
	width:100%;
}
</style>
<h1><img src="/img/h/h1-help-50.png"/>
مکاتبات</h1>

<img id="fsm" class='normal' data-state='normal' src='<?php echo SiteRoot?>/img/help/fsm.jpg' style="" onclick="zoomToggle()"/>
<script>

function zoomToggle(){
	$im = $('img#fsm');
	$st = $im.data('state');
	if($st=='normal')
	{
		$im.removeClass();
		$im.addClass('zoom');
		$im.data('state','zoom');
	}else
	{
		$im.removeClass();
		$im.addClass('normal');
		$im.data('state','normal');
	}
	
}
</script>
