<?php
class UserResetpassController extends BaseControllerClass
{
    function Start ()
    {
		j::Enforce("CreateUser");

	    if (count($_POST))
	    {
	    	$ID=$_GET['ID'];
    		$U=j::ODQL("SELECT U FROM MyUser U WHERE U.ID=?",$ID);
    		if (!$U)
    		{
    			$Error[]="کاربر وجود ندارد.";
    		}
    		else
    		{
    			$U=$U[0];
    		
	    		$r=$this->ResetPassword($U);
	    		$this->NewPass=$r;
	    		if ($r)
	    			$Result="با موفقیت تغییر یافت شد.";
	    		else 
	    			$Error[]="خطا";
    		}
	    }else{
	    	$U=ORM::Find("MyUser", $_GET['ID']);
	    	if (!$U)
	    		$Error[]="کاربر وجود ندارد.";
	    	
	    }
	    $f=$this->makeform();
	    
	    if(count($Error))$this->Result=false;
	    $this->Error=$Error;
	    $this->Result=$Result;
	   
    	$this->Autoform=$f;
    	return $this->Present();
    }
    
	
    function ResetPassword($User)
	{
		$newpass=b::generatePassword(6);
		$User->SetUsernamePassword($User->Username(),$newpass);	
		
		return $newpass;
	}
	
	function makeform(){
		$f=new AutoformPlugin("post");
		$f->AddElement(
				array(
						"Type"=>"submit",
						"Name"=>'Submit',
						"Value"=>"ایجاد رمز جدید",
				));
		return $f;
	}
}
?>
