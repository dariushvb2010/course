<?php
class UserPassController extends BaseControllerClass
{
    function Start ()
    {
        if(count($_POST))
        {
        	if($_POST['Retype']!=$_POST['NewPassword'])
        	{
        		$Error[]="رمز عبور با تکرار آن مطابقت ندارد .";
        	}
        	else if(j::$Session->ValidateUserCredentials(j::Username(), $_POST['OldPassword']))
        	{
        		if(j::$Session->EditUser(j::Username(), j::Username(),$_POST['NewPassword']))
        			$this->Result="رمز عبور تغییر یافت.";
        		else 
	        		$Error[]="خطا در تغییر رمز عبور ";
        			
        	}
        	else 
        	{
        		$Error[]="رمز عبور فعلی اشتباه می باشد ";
        	}
        	
        }
        $this->Error=$Error;
		if (count($Error))
		$this->Result=false;
		return $this->Present();
    }
}
?>