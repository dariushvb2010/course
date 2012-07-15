<?php
?>
<style>

#formdiv {
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
}
#usertable{
	margin:5px;
}
</style>

<h1><img src="/img/h/h1-review-50.png"/>
لیست کاربران</h1>

<div id="usertable">
	<?php
		$this->FileAutoList->Present(); 
	?>
</div>