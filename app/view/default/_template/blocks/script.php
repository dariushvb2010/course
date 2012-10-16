<script>
$("div#top>div#topright span").mouseover(function(){
	$(this).find("img.off").hide();
	$(this).find("img.on").show();
});
$("div#top>div#topright span").mouseleave(function(){
	$(this).find("img.off").show();
	$(this).find("img.on").hide();
});
$("div#top>div#topleft span").mouseover(function(){
	$(this).css("margin-top","0");
	
});
$("div#top>div#topleft span").mouseleave(function(){
	$(this).css("margin-top","7px");
	
});
</script>


<script>
$(function() {
	$("#homeMenu").button({
		icons: {
			secondary: "ui-icon-home",
			primary: "ui-icon-arrowthick-1-e"
		}
	});
	$("#homeMenu").click(function(){ document.location='<?php echo SiteRoot;?>';});
	$("#serviceMenu").button({
		icons: {
			secondary: "ui-icon-help",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#helpMenu").button({
		icons: {
			secondary: "ui-icon-help",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#managerMenu").button({
		icons: {
			secondary: "ui-icon-help",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#extraMenu").button({
		icons: {
			secondary: "ui-icon-locked",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$(".menu").menu({
		select: function(event, ui) {
			$(this).hide();
			return true;
		}
	}).popup();
//	$("#repeat").buttonset();
});
</script>