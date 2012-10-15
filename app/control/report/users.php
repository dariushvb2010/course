<?php
class ReportUsersController extends BaseControllerClass
{
    function Start ()
    {
    	j::Enforce('MasterHand');
    	$users = ORM::Query('MyUser')->GetAll();
    	
    	$al = new AutolistPlugin($users);
    	$al->ObjectAccess = true;
    	//$al->SetHeader('Select', 'انتخاب');
    	$al->SetHeader('FirstName', 'نام');
    	$al->SetHeader('LastName', 'نام خانوادگی');
    	$al->SetHeader('SaleVorod', 'سال ورود');
    	$al->SetHeader('Email', 'پست الکترونیک');
    	$this->List = $al;
    	return $this->Present();
    }
    
    
	
}
?>
