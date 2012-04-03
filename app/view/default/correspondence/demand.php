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
	margin-top: 30px;
}
form input[type='text'] {
	width:150px;
}
#data{
	width:75px;
	height: 15px;
	text-align: center;
	border: none;
}
#file-uploader{
	margin-right: 275px;
    margin-top: 5px;
}

#cal{
	background: none repeat scroll 0 0 white;
    border: 1px solid;
    margin-right: -86px;
    padding: 2px;
}
<?php $this->Form->PresentCSS();?>
</style>
<h1></h1>


<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>


	<a href='/help/#correspondence_demand'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left; margin-right:-30px;' />
	</a>
	
<div>
	<?php if (!$this->Result) { 
		$this->Form->PresentHTML();
	}?>
</div>

<script>
var uploader = new qq.FileUploader({
    // pass the dom node (ex. $(selector)[0] for jQuery users)
    element: document.getElementById('file-uploader'),
    // path to server-side upload script
    action: '<?php echo SiteRoot; ?>/servic/upload',
    allowedExtensions: ['xml','jpg','gif','jpeg','png'],
    sizeLimit: 3200000,
    hidden_input_ID: 'imglist',
    messages: 
    {
	    typeError: "مورد قبول است  {extensions} پسوند فایل داده شده اشتباه است. تنها  پسوند های ",
	    sizeError: "{sizeLimit}اندازه ی فایل تصویر نباید بیش تر از  ",
	    minSizeError: "{file} is too small, minimum file size is {minSizeLimit}.",
	    emptyError: "{file} is empty, please select files again without it.",
	    onLeave: "فایلی در حال آپلود شدن است اگر شما صفحه را ترک کنید آپلود شدن فایل متوقف می شود"
    }
});
/*
Calendar.setup(
  {
    inputField  : "data",         // ID of the input field
    displayArea : "data",
    ifFormat    : "%Y/%m/%d",    // the date format
    button      : "date_btn",       // ID of the button
    dateType	: "jalali",
    position    : [$("#data").offset().left-($("#data").outerWidth(true)/2+15),$("#data").offset().top+$("#data").outerHeight(true)+9]
  }
);
*/
<?php $this->Form->PresentScript();?>
</script>