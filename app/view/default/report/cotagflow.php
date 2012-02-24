<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir="rtl"><head>
	<link rel="stylesheet" href="<?php echo SiteRoot; ?>/style/base.css" />
	<style>
		div.progress{margin:6px 2px;}
		p.footinfo{margin:0; padding:0; font-size:smaller;}
		.CGFAnimator {
			font-family:"Tahoma";
			font-size:small;
			padding:2px;
			float: left;
	 		width:16px; 
	 		height:16px; 
			display:none;
		}
	</style>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head><body>
<script src="<?php echo SiteRoot;?>/script/jquery/ui1.8.14/js/jquery-1.5.1.min.js" ></script>
<script>
//CGF = CotaG Flow 
var CGF={
	Cotag:"6062323"
	,serviceRootUrl:"<?php echo SiteRoot."/service/" ?>"
	,underProcess:null
	,callService:function(title,params,callback)
	{
    	$.get(CGF.serviceRootUrl+title+"?output=json",
    			params
    			,callback
    		);			
	}
	,receive:function()
	{
		if (CGF.underProcess) return;
		CGF.processing();
		CGF.Cotag=$(":text.CGFField").val();
		CGF.callService("cotagflow",
				{"Cotag":CGF.Cotag}
				,CGF.receiveCallback
			);			
	}
	,receiveCallback:function(data)
	{
		
		CGF.processDone();
		eval("x=" + data);
		var div=$("div.CGFList");
		if(x.Err=='1')
		{
			div.append("<span style='color:red;'>اظهارنامه یافت نشد.</span>");
			return;
		}
		if(x.Err=='2')
		{
			div.append("<span style='color:red;'>هیچ فرآیندی یافت نشد.</span>");
			return;
		}
		var i=0; 
		for(i=0; i< x.length; i++)
		{
			div.append("<div class='progress'></div>");
			cdiv=div.find("div.progress:last");
			cdiv.append("<span style='color:blue;'>" +(i+1)+ "- " + x[i].Title + ": </span>");
			cdiv.append("<span class='summary'>"+ x[i].Summary + "</span><br/>");
			cdiv.append("<p class='footinfo'></p>");
			foot=cdiv.find("p:last");
			foot.append("<span> توسط " + x[i].User + "  </span>");
			foot.append("<span dir='rtl'> در تاریخ " + x[i].Time + "</span>");
			
		}
	}
	
	,processing:function()
	{
		CGF.underProcess=true;
		$("div.CGFList").html("");
		$(".CGFAnimator").show();
		$(".CGFSendButton").attr("disabled","disabled");
		$(".CGFStatus").attr("diasbled","disabled");
	}
	,processDone:function()
	{
		CGF.underProcess=null;
		$(".CGFSendButton").attr("disabled","");
		$(".CGFAnimator").fadeOut();
	}
	,button:function()
	{
		CGF.receive();
	}
};
$(document).ready(function(){
	$(":text.CGFField").click(function()
	{
		
	});
});

</script>
<div id="main">
<form class="CGFForm" onsubmit="CGF.button(); return false;">
	<div class="input" style="text-align:center;">
		<img class="CGFAnimator" src="<?php echo SiteRoot;?>/img/loading.gif" title="درحال کار کردن..." />
		<input type="text" class="CGFField" maxlength="<?php echo CotagLength;?>" />
		<input type="submit"   class="GetReport" class="CGFSendButton"value="گزارش گیری"/>
	</div>
</form>
<div class="CGFList">

</div>
</div><!-- main -->
</body></html>