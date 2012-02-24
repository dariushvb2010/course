<?php
/**
 * Internationalization Interface
 * @var I18nPlugin
 */
j::$App->i18n=new I18nPlugin($this);
j::$i18n= &j::$App->i18n;


//public static j::$ORM;
if (!defined("DoctrineCommandLine") or !constant("DoctrineCommandLine"))
{
	$ORM=new DoctrinePlugin();
	j::$ORM=$ORM;
}
?>