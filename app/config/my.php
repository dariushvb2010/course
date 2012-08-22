<?php

/**
* Identity
*
* Define your jFramework powered web application here. Set at least a version, a Name and a
* title for your application. Name would better follow identifier rules.
*/
reg( "app/version", "3.4" );
reg( "app/name", "CustomsReview" );
reg( "app/title", "نرم‌افزار جامع بازبینی گمرک" );
reg( "app/sherkat/title","پژوهشکده سامانه های هوشمند کاربردی دانشگاه تهران");


/** custom application settings
 * 
 */
reg("my/DirectDownloadLink",SiteRoot."/file/trunk/jf3.1.22.tar.gz");

reg("link/switch/url","http://10.32.0.18:8080/Swich");
define ('GateCode',"45100");
define ('GateName',"بوشهر");

?>