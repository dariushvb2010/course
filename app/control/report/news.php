<?php
class ReportNewsController extends BaseControllerClass
{
    function Start ()
    {
    	$News = ORM::Query('News')->GetAllArray();
    	$News[] = array('Description'=>'ارایه دهندگان: محمدکاظم تارم - داریوش جعفری دانشجویان ترم هفت کارشناسی');
    	$News[] = array('Description'=>'زمان و مکان کلاس: شنبه ها ۱۶-۱۴:۳۰ کلاس ۱۰۳.');
    	$News[] = array('Description'=>'اولین جلسه : شنبه ۶ آبان');
    	$this->News = $News;
    	return $this->Present();
    }
    
    
	
}
?>
