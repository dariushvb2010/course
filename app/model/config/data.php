<?php

class ConfigData
{
	static $CONFIG_STYLE=array(
		"Main"=>array(
		),
		"Alarm"=>array(
			 "Cotag"=>"تحویل ندادن اظهارنامه توسط دفتر کوتاژ",
			 "Provision14"=>"ماده 14",
			 "Provision15"=>"ماده 15",
		),
		"Event"=>array(
		)
	);
	
	 static $GROUPS=array(
		 "Admin"=>"مدیریت",
		 "CotagBook"=>"دفتر کوتاژ",
		 "Archive"=>"بایگانی بازبینی",
		 "Raked"=>"بایگانی راکد",
		 "Reviewer"=>"کارشناس بازبینی",
		 "Correspondence"=>"مکاتبات",
		 "Typist"=>"تایپیست",
		 "Programmer"=>"برنامه نویسی"
	 );
	
	 /**
	  * Name=>array(Value,DeleteAccess,Comment,Style)
	  */
	 static $MAIN=array(
	 
	 );
	 
}
