<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir='rtl'>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title><?php
		if (isset($Title))
			echo $Title;
		else
		    echo j::Registry("app/title");
		?></title>
		<?php 
		if ($Extra)
			echo $Extra;
		?>
		<link rel="shortcut icon" href="/img/jlogo.png" />
		<link rel="stylesheet" href="/style/base.css" />
		<link rel="stylesheet" href="/style/print.css" />
		
		
		<link rel="stylesheet" href="/script/calendar/skins/calendar-blue.css" />
		
		<link rel="stylesheet" href="/script/jqueryui/themes/base/jquery.ui.all.css" />
		
		<script src='/script/jquery/ui1.8.14/js/jquery-1.5.1.min.js'></script>
		<script src='/script/jquery/ui1.8.14/js/jquery-ui-1.8.14.custom.min.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.core.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.position.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.widget.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.button.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.autocomplete.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.datepicker.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.dialog.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.menu.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.menubar.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.mouse.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.popup.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.progressbar.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.tabs.js'></script>
		<script src='/script/jqueryui/ui/jquery.ui.slider.js'></script>
		
		<script src='/script/calendar/calendar.js'></script>
		<script src='/script/calendar/calendar-setup.js'></script>
		<script src='/script/calendar/jalali.js'></script>
		<script src='/script/calendar/lang/calendar-fa.js'></script>
		
	</head>
	<body>
		<a name='top'></a>
		<div id="body" class="bazbox">
			<?php  require_once 'blocks/script.php';?>
			<?php  require_once 'blocks/menublock.php';?>
			<?php  echo $Content;?>
		</div> <!-- Body div -->
		<?php  require_once 'blocks/foot.php';?>
		<link rel="stylesheet" href="/style/fprint.css" />
	</body>
</html>