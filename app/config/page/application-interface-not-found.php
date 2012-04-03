<?php
// $Request, $Module
// you can use the constants at above
$j=new jURL();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir="rtl"><head>
	<link rel="stylesheet" href="<?php echo SiteRoot;?>/style/base.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>صفحه یافت نشد</title>
	<style>
	h1{color:#d00; border-bottom:3px double #d00;}
	</style>
</head><body>
	<div id="top"></div>
	<div id="body">
		<h1><img src="<?php echo SiteRoot;?>/img/h/h1-alert-red-50.png"/>
		صفحه یافت نشد</h1>
		<p>صفحه درخواستی شما وجود ندارد:</p><p>
		<b><?php echo $Request?></b>
		در <b><?php echo $j->URL()?></b></p>
	</div>
</body></html>

