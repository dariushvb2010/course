<?php
try {
	require_once(dirname(__FILE__)."/install/index.php");
	die();
}catch (Exception $e){
var_dump($e);
die();	

if (defined("jfasalib") && constant("jfasalib"))
{
	require_once(dirname(__FILE__)."/_japp/controller.php");
}
else 
{
/**
 * jFramework start point, when mod_rewrite is disabled
 */	

?>
<h1>jFramework</h1>
It seems that your Apache's mod_rewrite is not functioning properly. You should not see this notice if your 
mod_rewrite is enabled and you have access to use it, or maybe you haven't put the <b>.htaccess</b> file to your jFramework's
root.
<br/>
Visit <A href='http://wiki.jframework.info/index.php/Manual'>http://wiki.jframework.info/index.php/Manual</A> for more info. 
<?php
}
}
