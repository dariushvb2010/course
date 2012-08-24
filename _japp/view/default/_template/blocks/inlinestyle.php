<style>
body{
	background-image:url('/img/background2.png') ;
}
div#http{display:table-cell; width:30%;}
.security {
	border:1px gray solid;
	margin:2px auto;
	-moz-border-radius:5px;
	overflow:none;
	display:block;
	text-decoration:none;
	text-align:center;

}
#securityWarning {
	width:350px;
	padding:1px;
	font-size:small;	
	background-color:darkred;
	color:white;
}
#securityConfirm {
	width:220px;
	padding:1px;
	background-color:darkgreen;
	font-size:11px;	
	color:white;
	border:1px green solid;
}
#securityWarning:HOVER {
	background-color:black;
	color:red;
}
.security img {
	position:relative;
	top:2px;
	border:0px solid;
}
<?php CotagflowPlugin::PresentCSS();?>
</style>