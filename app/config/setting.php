<?php
/**
 * This is the jFramework General Settings file.
 * This file is loaded before everything happens in jFramework (even before ApplicationController construction)
 * it holds the general settings required by php or jFramework
 * 
 * Changing any of these options may change the whole behaviour of your application, so caution!
 */
$v=phpversion();
$v=explode(".",$v);

define("BR", "<br/>"); # very common best practice!

if ($v[0]<=5 && $v[1]<3) //only disabled them if PHP version < 5.3 
	set_magic_quotes_runtime(false); #jFramework hates magic quotes!


# not recommended yet, experimental
define("jf_LazyLoad",false); //Enables Lazy Loading. This would make your application much faster if you're not using many features
//and will make it little slower if you're using its full potential
?>