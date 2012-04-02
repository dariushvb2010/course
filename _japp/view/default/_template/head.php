<?php
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php tr('dir="ltr"');?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php
    echo reg("app/title");
?></title>
<link rel="shortcut icon" href="/img/jlogo.png" />
<script src='/script/jquery/132min.js'></script>
<script src='/script/jquery/reflect.js'></script>
</head>
<body>
<?php if (!isset($_GET['noheader'])) { ?>
<style>
body {
	background-color: #0E2E3E;
	margin: 0px;
}

p {
	border: 0px;
	outline: none;
	margin: 0px;
	padding: 0px;
}

a {
	text-decoration: none;
	color: inherit;
}
a:HOVER {
	text-decoration: underline;
}


.top_navitem {
	padding: 4px 4px 0px;
	font-size: 11px;
	margin: 0px;
	border: 0px;
	outline: none;
	-moz-border-radius: 5px;
	cursor: pointer;
}

.top_navitem:HOVER {
	text-decoration: underline;
}
#top_menu {
	height:20px;
}

#top_navitems {
	width: 950px;
	margin: auto;
	text-align: left;
	color: white;
	font-size: 14px;
	padding-top: 3px;
	padding-left: 50px;
}


#body {
	background-color: #FEFEFE;
	min-height: 400px;
	margin: auto;
	padding-top: 0px;
	margin-bottom:10px;
}
span#language {
	<?php tr("float: right;")?>
	padding:2px 5px 2px 5px;
}
span#language a {
	color:#999999;
	font-size:smaller;
	padding:4px;
	text-decoration:none;
}
span#language a:HOVER {
	text-decoration:underline;
}

</style>

<div id="top_menu">
<div id="top_navitems">
<?php
if ($this->Session->UserID)
{
    echo "" . $this->Session->Username() . "";
    ?>
    <a href="/sys/logout" id="username"
	style="font-size: 9px; ">
<?php tr("Sign Out");?> </a>
    <?php
}
else
{
    ?>
    <a href="sys.login"
	style="font-size: 9px; font-family: smallfonts;"><?php tr("Sign
In");?></a>
    <?php
}
?>
<span id='language'>
<?php 
	$langs=j::Registry("jf/i18n/langs");
	if ($langs)
	{
		foreach ($langs as $k=>$l)
		{
			if ($k=="default") continue;
			if ($k=="current") {$currentLang=$l; continue;}
			{?><a href='?lang=<?php echo $k?>'><?php echo $l?></a><?php }
		}
?>
<?php }?>	
</span>
</div>

</div>
<div id="body">
<?php }?>