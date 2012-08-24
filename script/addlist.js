
	var a=0;
	
	function IsNumeric(input){
	    var RE = /^-{0,1}\d*\.{0,1}\d+$/;
	    return (RE.test(input));
	}
	function trim(str)
	{
	        return str.replace(/^\s+|\s+$/g,"");
	}
	function trimZero(str)
	{
		return str.replace(/^0+/, '');
	}
	function addRow()
	{
		var cotag=$('#cotag').val();
		cotag=trimZero(cotag);
		cotag=trim(cotag);
		if(!CotagPattern.test(cotag))
		{
			alert("کوتاژ ناصحیح است ");
			return false;
		}
		
		$("#list").show(200);
//		if(a>50)
//		{
//			alert("حداکثر 50 کوتاز می توان تخصیص داد؛");
//			return false;
//		}
		a++;
		var flag =true;
		$.each($("td"),function (i,n){
			if(($(this).text())==(cotag))
					flag=false;
		});
		if(flag)
			$(".autolist").prepend("<tr><td>"+a+"</td><td><input class='item' checked='checked' type='checkbox' name='item[]' value="+cotag+"></td><td>"+cotag+"</td></tr>");
		else
				alert("کوتاژ تکراری است");
		$('#cotag').val('');
	}
	
	$("#SelectAll").click(function()
			{
				$(".item").attr("checked",$("#SelectAll").attr("checked"));
			});
	$(function(){
		$("#list").hide();
		$("input[name='Cotag']").focus();
	});
	$("#addButton").click(function()
			{
				addRow();
			}
		);
			
	$("#cotag").keydown(function(event){
		  if(event.keyCode==13)
			  addRow();
		});
	$('#itemsubmit').click(function(){
		if($(".item:checked").length==0)
		{
			alert("هیچ کوتاژی انتخاب نشده است");
			return false;
		}
	});
	