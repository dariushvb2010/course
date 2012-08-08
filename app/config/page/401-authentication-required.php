<?php
//	    	define ("Permission",$Permission);
	header("HTTP/1.0 401 Authentication Required",false);
$j=new jURL();
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir="rtl"><head>
<link rel="stylesheet" href="<?php echo SiteRoot;?>/style/base.css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>401 دسترسی غیرمجاز</title>
<style>
h1{color:#d00; border-bottom:3px double #d00;}
</style>
</head><body>
<div id="top"></div>
<div id="body" class="bazbox">
<h1><img src="<?php echo SiteRoot;?>/img/h/h1-alert-red-50.png"/>
دسترسی غیرمجاز</h1>
<p>شما اجازه دسترسی به این صفحه را ندارید.</p><p>
<b><?php echo $j->RequestFile();?></b> در <b><?php echo $j->URL();?></b></p>
</div>
</body></html>
<?

?>