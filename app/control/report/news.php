<?php
class ReportNewsController extends BaseControllerClass
{
    function Start ()
    {
    	$this->News = ORM::Query('News')->GetAllArray();
//     	$News[] = 'زمان و مکان کلاس متعاقبا اعلام خواهد شد.';
//     	$News[] = 'ارایه دهندگان: محمدکاظم تارم - داریوش جعفری دانشجویان ترم هفت کارشناسی';
//     	$this->News = $News;
    	return $this->Present();
    }
    
    
	
}
?>
