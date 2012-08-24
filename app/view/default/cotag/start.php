<?php
$this->HasError=(count($this->Error));
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
#BarcodeContainer{
	text-align:center;
	margin:10px;
}
#BarcodeContainer div{
	text-align:center;
	margin:auto;
	background-color:#fff;
	width:500px;
	border:double;
	padding:20px;
}
<?php if($this->HasError){?>
#body{background:red; -moz-box-shadow:10px 10px 50px 100px #FAFAFA inset; box-shadow:10px 10px 50px 100px #FAFAFA inset;}
<?php }?>
@media print {
	#exceptBarcode{
		display:none;
	}
	#datetime.Footer {
	
		display:none !important; 
	}
	#Copyright{
	{
		visibility:hidden;
		display:none !important; 
	}
	body div#body{
		border:none !important;
		border-color:transparent;
	}
}
</style>
<div id='exceptBarcode'>
<h1><img src="/img/h/h1-start-50.png"/>
ورود اظهارنامه</h1>
<form method='post'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
	if($this->HasError)
		AutosoundPlugin::EchoError("error3");
?>
	<a href='/help/main#CotagBook'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
	</a>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' id='Cotag' value='<?php echo substr($this->Cotag,0,3);?>'/>

</div>
<div>
	<input type="checkbox" name='print' <?php if ($this->IsPrint==true || !isset($this->Cotag) )echo "checked=checked" ;  ?>><a href='#' id='prints'>چاپ بارکد</a>
</div>
<input type='submit' id='sub' value='وصول' />
</form>
</div>
<?php if( !($this->Result==false) && $this->IsPrint==true){ ?>
<div id="BarcodeContainer" align="center">
	<div>
		<?php $printURL= jURL::Root()."/barcode?number=".urlencode($this->Cotag)."&width=3&height=100&font=18";?>
		<img src='<?php echo $printURL;?>'>
	</div>
</div>
<?php }?>
<script>
function IsNumeric(input){
	    var RE = "<?php echo b::Cotag_jsPattern; ?>";
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
	
		
function setCursor(node,pos){

    var node = (typeof node == "string" || node instanceof String) ? document.getElementById(node) : node;

    if(!node){
        return false;
    }else if(node.createTextRange){
        var textRange = node.createTextRange();
        textRange.collapse(true);
        textRange.moveEnd(pos);
        textRange.moveStart(pos);
        textRange.select();
        return true;
    }else if(node.setSelectionRange){
        node.setSelectionRange(pos,pos);
        return true;
    }

    return false;
}
$('#prints').click(function ()
{
	 window.open("./bar?cotag="+$("form input[name='Cotag']").val()+"&chk=1");
});

$(document).ready(function(){
	
	$("form input[name='Cotag']").focus();
	setCursor("Cotag",$('#Cotag').val().length);
<?php  if(!($this->Result==false) && $_POST['print']==true){?>
	
			window.open("./bar?cotag="+<?php echo $_POST['Cotag'];?>);

<?php }?>
});

	<?php 
	if($this->HasError){
		?>
		function back()
		{
			document.getElementById("body").style.background="#FAFAFA";
		}
		setTimeout(back,2000);
	<?php }?>
</script>