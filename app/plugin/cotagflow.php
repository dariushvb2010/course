<?php 
class CotagflowPlugin
{
	
	static function PresentCSS()
	{
		
		?>
		div#CGFBack{position:fixed; background:black; top:0px; left:0px;
			display:none;
		 	filter:alpha(opacity=70);opacity:.7; 
		 	height:2000px; width:2000px; z-index:999}
		div#CGFMain.CGFMain{
			display:none;
			position:absolute; top:30px; right:100px; 
			background:transparent; z-index:1000;
			margin:0; padding:10px;
		}
		div#CGFMain form#CGFForm{
			display:inline-block;
			vertical-align:top;
			background:#eee; z-index:1000;
			border:1px solid #ccc; -moz-border-radius:5px;
			margin:0; padding:10px;
		}
		div#CGF{
			background:#f6f3ff; z-index:1000;
			border:1px solid #ccc; -moz-border-radius:5px;
			margin:0; padding:0 10px 10px 0;
		}
		div#CGFMain div#CGFList.CGFList{
			max-height:500px;
			overflow:auto;
		}
		div#CGF{display:none;}
		div#CGFList div.progress{margin:6px 2px;  padding:4px;}
		p.footinfo{margin:0; padding:0; font-size:smaller;}
		.CGFAnimator {
			font-family:"Tahoma";
			font-size:small;
			padding:2px;
			width:16px;
			height:16px;
			display:none;
		}
		#CGFForm span#CGFDelete{ float:right; margin:-7px;}
		<?php 
	}
	static function PresentScript()
	{
		?>
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
			CGF.callService("cotagflow", {"Cotag":CGF.Cotag}, CGF.receiveCallback );
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
			$("div#CGFBack").show("slow");
			$("div#CGF").show("slow");
			CGF.underProcess=null;
			$(".CGFSendButton").attr("disabled","");
			$(".CGFAnimator").fadeOut();
		}
		,hide:function()
		{
			$("div#CGFBack").hide("slow");
			$("div#CGF").hide("slow");
			$("div#CGFMain").hide();
		}
		,button:function()
		{
			CGF.receive();
		}
		,toggle:function()
		{
			$("div#CGFMain").toggle("slow");
		}
	};
	$(document).ready(function(){
		$(":text.CGFField").click(function()
		{
	
		});
	});
		<?php 
	}
	static function PresentHTML()
	{
		?>
	<div id="CGFMain" class="CGFMain">
		<form id="CGFForm" onsubmit="CGF.button(); return false;">
		<span  id="CGFDelete" dir='ltr' style="float:right;"><img onclick='CGF.hide();' src='<?php echo SiteRoot;?>/img/delete-blue-20.png' /></span>
			<div class="input" style="text-align:center;">
				<span style="width:20px; display:inline-block; margin:0; padding:0"><img class="CGFAnimator" src="<?php echo SiteRoot;?>/img/loading.gif" title="درحال کار کردن..." /></span>
				<input type="text" class="CGFField" />
				<input type="submit"   class="GetReport" id="CGFSendButton" style="width:100px;" value="گزارش گیری"/>
			</div>
		</form>
		<div id="CGF">
			
			<div id="CGFList" class="CGFList">
			</div>
		</div>
	</div><!-- main -->
	 <div id="CGFBack"></div> 
		<?php 
	}
	
}