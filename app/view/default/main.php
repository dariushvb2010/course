<style>
#sideMenu {
	width:200px;
	margin:8px;
	padding:5px;
	border:3px double gray;
	float:left;
	font-size:small;
}
#sideMenu a {
	display:block;
}
</style>


<h2><?php echo reg("app/title")." نسخه ".reg("app/version");?></h2>
<div id='sideMenu'>
		<div style='text-align:center;font-weight:bolder;font-size:1.2em;'>
		دسترسی سریع
		</div>
		<hr/>
		<a href='/user/create'>
				ثبت نام
		</a>
		<a href="/report/poll">آمار ثبت نام</a>
</div>
<p>
خوش آمدید.
</p>