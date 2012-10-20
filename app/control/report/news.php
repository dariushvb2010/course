<?php
class ReportNewsController extends BaseControllerClass
{
    function Start ()
    {
    	$News = ORM::Query('News')->GetAllArray();
    	$News[] = array('Description'=>'ارایه دهندگان: محمدکاظم تارم - داریوش جعفری دانشجویان ترم هفت کارشناسی');
    	$News[] = array('Description'=>'زمان و مکان کلاس متعاقبا اعلام خواهد شد.');
    	$this->News = $News;
    	return $this->Present();
    }
    
    
	
}
?>
