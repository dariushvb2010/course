<?php
?>
<style>
a[href]  {
	text-decoration:underline;
}

</style>
<table border="0" cellpadding="0" cellspacing="0" width="98%" align="center">
<tr><td valign="top" width="200" style="padding-right:15px;">

<ul style="list-style-type: square;text-indent: 0px;margin: 0px;padding-left:16px;font-size:small;">
  <li> <a class="main"><?php tr("Administration");?></a>
		<ul>
  			<li><a href="/sys/panel/dashboard" class="sub"><?php tr("Dashboard");?></a></li>
  			<li><a href="/sys/users/online" class="sub"><?php tr("Online Visitors");?></a></li>
  			<li><a class="sub" href="/sys/logs/view"><?php tr("View Logs");?></a></li>
		</ul>
  </li>
  <li> <a class="main"><?php tr("Users");?></a>
		<ul>
  			<li><a class="sub" href="/sys/users/add"><?php tr("Add");?></a></li>
  			<li><a class="sub" href="/sys/users/edit"><?php tr("Edit");?></a></li>
  			<li><a class="sub" href="/sys/users/remove"><?php tr("Remove");?></a></li>
  			<li><a class="sub" href="/sys/users/assign"><?php tr("Assign Roles");?></a></li>
  			<li><a class="sub" href="/sys/users/unassign"><?php tr("Unassign Roles");?></a></li>
		</ul>
  </li>
  <li> <a class="main"><?php tr("RBAC");?></a>
		<ul>
  			<li><a class="sub" href="/sys/rbac/addpermission"><?php tr("Add"); echo " "; tr("Permission");?></a></li>
  			<li><a class="sub" href="/sys/rbac/editpermission"><?php tr("Edit"); echo " "; tr("Permission");?></a></li>
  			<li><a class="sub" href="/sys/rbac/deletepermission"><?php tr("Delete");tr("Permissions");?></a></li>
  			<li><a class="sub" href="/sys/rbac/addrole"><?php tr("Add"); echo " "; tr("Role");?></a></li>
  			<li><a class="sub" href="/sys/rbac/editrole"><?php tr("Edit"); echo " "; tr("Role");?></a></li>
  			<li><a class="sub" href="/sys/rbac/deleterole"><?php tr("Delete"); echo " "; tr("Roles");?></a></li>
  			<li><a class="sub" href="/sys/rbac/assign"><?php tr("Assign");?></a></li>
  			<li><a class="sub" href="/sys/rbac/unassign"><?php tr("Unassign");?></a></li>
		</ul>
  </li>
  <li> <a class="main"><?php tr("Development");?></a>
		<ul>
  			<li><a href="/sys/modules/add" class="sub"><?php tr("Add Module");?></a></li>
  			<li><a class="sub" href="/sys/panel/development/options"><?php tr("View Options");?></a></li>
  			<li><a class="sub" href="/sys/panel/development/registry"><?php tr("View Registry");?></a></li>
  			<li><a href="/sys/panel/development/translate" class="sub"><?php tr("Translate");?></a></li>
		</ul>
  </li>
  
</ul>
</td><td valign="top">
<iframe id="target" width="100%" style="border:0px solid;padding:0px;margin:0px;min-height:500px;outline:none;" frameborder="0"></iframe>
</td></tr>
</table>
<script>
$("a.main,a.sub").click(function(e){
	return present(e.target.href);
});
function present(url)
{
	$("#target").attr("src",url+"?noheader");
	return false;
}
$(function() {present("<?php echo SiteRoot?>/sys/panel/dashboard");});
</script>