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
</style>
<h1></h1>


<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<form method='post'>

	<a href='/help/#correspondence_demand'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left; margin:0;' />
	</a>
	<input type='hidden' name='Cotag' value='<?php echo $this->Cotag;?>'>
	<input type='text' name='Cotag' value='<?php echo $this->Cotag;?>'>	
<div>
	<div id="file-uploader">       
    <noscript>          
        <p>Please enable JavaScript to use file uploader.</p>
        <!-- or put a simple form for upload here -->
    </noscript>         
</div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />
	<label>تصویر مطالبه نامه</label>
	<input type='file' name='Pic' accept="image/jpge" value="s"  />
	
</div>

<input type='submit' value='ارسال' />
</form>