
<h1>
وصول اظهارنامه های قدیمی از دفتر کوتاژ </h1>
 
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<form class="autoform" method="post">
	<p>
		<input name="Cotag"/>
	</p>
	<p>
		<input type="submit" value="تحویل گرفتن" name="register" />
	</p>
</form>
<script type="text/javascript"> const CotagPattern=<?php echo b::Cotag_jsPattern;?></script>
<script src='/script/addlist.js'></script>