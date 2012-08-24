<?php
//$File is usable here
// this is invoked when jFramework tried to present a file which does not exist (as a resource)
		header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir="rtl"><head>
	<link rel="stylesheet" href="<?php echo SiteRoot;?>/style/base.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>404 Not Found</title>
	<style>
	h1{color:#d00; border-bottom:3px double #d00;}
	</style>
</head><body>
	<div id="top"></div>
	<div id="body" class="bazbox">
		<h1><img src="<?php echo SiteRoot;?>/img/h/h1-alert-red-50.png"/>
		یافت نشد</h1>
		<p>آدرس درخواستی شما یافت نشد:</p><p>
		<b><?php echo basename($File) ?></b>.</p>
	</div>
</body></html>
<?

?>