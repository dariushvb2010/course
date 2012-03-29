<?php
?>
<style>

form {
	width:400px;
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
<?php $this->Form->PresentCSS();?>
</style>
<h1>ویرایش بازبینی اظهارنامه</h1>
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
<a href='./editselect'>انتخاب اظهارنامه بعدی</a>
<?php }?>

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