<?php
?>
<style>

form {
	width:80%;
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
	text-align:center;
}
.autoform div.even{
		background:rgb(128,190,232);
	}
	.autoform div.odd{
		background:rgb(236,245,253);
	}
	.autoform{
		background:rgb(236,245,253);
	}
<?php $this->Form->PresentCSS();?>
</style>
<h1><img src="/img/h/h1-review-50.png"/>
بازبینی اظهارنامه</h1>
<p>

</p>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<?php if (!$this->Result) { ?>
<?php $this->Form->PresentHTML();?>
<script>
<?php $this->Form->PresentScript();?>
</script>
<?php }
else
{?>
<a href='./select'>انتخاب اظهارنامه بعدی</a>
<?php }
?>
<script>

	$(".money").keyup(function (){
		ThousandCommas(this);
	});
</script>
<script>
	function commas(obj){
		formatNum(obj);
	}
	function ThousandCommas(obj){
	var current=obj.value;
	var after=current;

	//current=current.replace("/,/g","");
	current=current.replace(new RegExp(",", 'g'),"");
	
	var decimalpoint=current.lastIndexOf(".");

	var n;
	var d;
	if(decimalpoint>=0){
	var f=current.split(".");
	d=f[1];
	n=f[0];
	}
	else{
	n=current;
	}

	var index=parseInt((n.length-1)/3);
	
	if(index!=0){
	var prefixIndex=n.length-index*3;
	after=n.substr(0,prefixIndex)+","+n.substr(prefixIndex,3);
	for(var i=2;i<=index;i++){
	after+=","+n.substr(prefixIndex+3*(i-1),3);
	}

	if(decimalpoint>=0){
	after+="."+d;
	}
	}
	obj.value=after;
	}
</script>

<script>

//$("form#f1 *[name='m']").hide("slow",0.33);

	//alert($("form input[value='Other']").attr("checked"));
	/*
	Problem=$("form input[value='0']");
	NoProblem=$("form input[value='1']");
	Problem.change(function(){
		
		//$("form input[vlaue=Value]").css("display","none");
		alert("ok");
		if(Problem.is(":checked"))
			alert("it is checked");
		else if(NoProblem.is(":checked"))
			alert("another is checked");
		g=document.getElementsByName("Difference[]");
		d=$(".autoFormContainer");
		$.each(d,function(key,value){
			//alert(key+" "+value);
			//alert(value.tagName);
			if(key==2 )
				this.style.display="inline";
			if(key==3)
			{
				//alert(key);
				//this.style.display="none";
				$(this).hide();
			}
			
		});
		/*
		//d.fadeTo("slow",0.33);
		//d.hide("slow");
		for(var i=0; i<g.length; i++)
		{
			g[i].style.display="none";
			
		}*/
	//});
	//document.write("reach to end");
	
</script>