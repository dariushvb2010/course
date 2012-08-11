<script>
<?php CotagflowPlugin::PresentScript();?>
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
	$("#archiveMenu").button({
		icons: {
			secondary: "ui-icon-folder-collapsed",
				primary: "ui-icon-triangle-1-s"
		}
	});
	$("#cotagMenu").button({
		icons: {
			secondary: "ui-icon-grip-dotted-vertical",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#scanMenu").button({
		icons: {
			secondary: "ui-icon-grip-dotted-vertical",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#reviewMenu").button({
		icons: {
			secondary: "ui-icon-search",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#correspondenceMenu").button({
		icons: {
			secondary: "ui-icon-note",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#staticArchiveMenu").button({
		icons: {
			secondary: "ui-icon-trash",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#ManagerMenu").button({
		icons: {
			secondary: "ui-icon-star",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#reportMenu").button({
		icons: {
			secondary: "ui-icon-gear",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#helpMenu").button({
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