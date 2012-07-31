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


/** custom application settings
 * 
 */
reg("my/DirectDownloadLink",SiteRoot."/file/trunk/jf3.1.22.tar.gz");
define( "CotagLength",7);

reg("link/MohamadTavbal","http://10.64.0.18:8080");
?>