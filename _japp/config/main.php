<?php
reg("jf/profiler/TickCount",1000);





reg("jf/controller/DefaultController/metafile","default");
reg("jf/controller/DefaultController/classname","Default");


$this->LoadCustomSystemModule("config.lib.log",".");
$this->LoadCustomSystemModule("config.lib.session",".");
$this->LoadCustomSystemModule("config.lib.options",".");
$this->LoadCustomSystemModule("config.lib.rbac",".");
$this->LoadCustomSystemModule("config.lib.i18n",".");

$this->LoadCustomSystemModule("config.services",".");

$this->LoadCustomApplicationModule ( "config.static", "." ); # jFramework determination modules (reserved)


?>