<?php
//define("jF_OptionsTable","jf_options");
reg("jf/options/table/name","jf_options");
//define("jF_OptionsTable_ID","ID");
reg("jf/options/table/OptionID","ID");
//define("jF_OptionsTable_UserID","UserID");
reg("jf/options/table/UserID","UserID");
//define("jF_OptionsTable_OptionName","Name");
reg("jf/options/table/OptionName","Name");
//define("jF_OptionsTable_OptionValue","Value");
reg("jf/options/table/OptionValue","Value");
//define("jF_OptionsTable_OptionExpirationDate","Expiration");
reg("jf/options/table/ExpirationDate","Expiration");

define("TIMESTAMP_MINUTE",60);
define("TIMESTAMP_HOUR",TIMESTAMP_MINUTE*60);
define("TIMESTAMP_DAY",TIMESTAMP_HOUR*24);
define("TIMESTAMP_WEEK",TIMESTAMP_DAY*7);
define("TIMESTAMP_MONTH",TIMESTAMP_DAY*30);
define("TIMESTAMP_YEAR",TIMESTAMP_DAY*365);
define("TIMESTAMP_FOREVER",TIMESTAMP_YEAR*128);

//define("jF_Options_DefaultTimeout",TIMESTAMP_FOREVER);
reg("jf/options/timeout/default",TIMESTAMP_FOREVER); 
//define("jF_Options_SweepRatio",.005); //increase this ratio if you use options rarely
reg("jf/options/SweepRatio",.005); //increase this ratio if you use options very rarely
?>