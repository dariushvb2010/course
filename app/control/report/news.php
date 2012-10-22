<?php
class ReportNewsController extends BaseControllerClass
{
    function Start ()
    {
    	$News = ORM::Query('News')->GetAllArray();
    	$News[] = array('Description'=>'ارایه دهندگان: محمدکاظم تارم - داریوش جعفری دانشجویان ترم هفت کارشناسی');
    	$News[] = array('Description'=>'زمان و مکان کلاس: شنبه ها ۱۶-۱۴:۳۰ کلاس ۱۰۳.');
    	$News[] = array('Description'=>'اولین جلسه : شنبه ۶ آبان');
    	$News[] = array('Description'=>'برای حضور در کلاس ثبت نام در سایت کافی است');
    	$this->News = $News;
    	return $this->Present();
    }
    
    
	
}
?>
