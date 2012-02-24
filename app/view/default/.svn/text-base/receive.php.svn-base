<?php
?>

<style>

form {
	width:500px;
	margin:auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
form input[type='submit'] {
	width:200px;
	margin:5px;
}

form input[type='text'] {
	width:150px;
}
#Select{
	margin:auto;
}


</style>

<h1> دریافت اظهارنامه</h1>


<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
 if (isset($this->Mails))
		foreach($this->Mails as $m)
		{
			echo "<a href='?ID=".$m->ID()."' >".$m->Num()." "."</a><br/>";
		}
	else if(isset($this->Posts))
	{
		?>
		<form>
		<label>کوتاژ</label><input id='cotag'></br>
		<input type="button" id='addButton' value = 'دریافت'>
		</form>
		
		<form method='post'>
		<?php 
		$this->List->Present();
		?>
		<input type='hidden' value='<?php echo $_GET["ID"] ?>' name='id'/> 
		<input type='submit' value = 'دریافت'/>
		</form>
		<?php
	}
	else if(isset($this->ResultList))
	{
		?>
		<div id='rangeResult'>
		<h2>نتایج دریافت از بایگانی بازبینی</h2>
		<?php
		$this->ResultList->Present();
		?>
		</div>
		<?php 
	}?>
		


<script>
	$("#SelectAll").click(function()
			{
				$(".item").attr("checked",$("#SelectAll").attr("checked"));
			});

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
		if(!IsNumeric(cotag) || parseInt(cotag)<1 || cotag.length!=<?php echo CotagLength?>)
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
				{
					//alert("hh");
					$(this).prev().children(':first-child').attr("checked","checked");
				}
		});
		//if(flag)
		//	$(".autolist").prepend("<tr><td><input class='item' checked='checked' type='checkbox' name='item[]' value="+cotag+"></td><td>"+cotag+"</td></tr>");
		//else
			//	alert("کوتاژ تکراری است");
		$('#cotag').val('');
		return false;
	}
	
	$("#SelectAll").click(function()
			{
				$(".item").attr("checked",$("#SelectAll").attr("checked"));
			});
	
	$("#addButton").click(function()
			{
				addRow();
			}
		);
			
	$("#cotag").keydown(function(event){
		  if(event.keyCode==13)
		  {
			   addRow();
		  		return false;
		  }
		});
	
	
</script>




