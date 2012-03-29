<?php
class UserCreateController extends BaseControllerClass
{
    function Start ()
    {
		j::Enforce("CreateUser");
		$f=new AutoformPlugin("post");
    	$f->AddElement(
	    	array(
			"Type"=>"text",
	    	"Label"=>"نام کاربری",
	    	"Validation"=>"/^[a-zA-Z0-9]{3,}$/",
	    	"Name"=>"Username",	    	
	    	));
    	$f->AddElement(
	    	array(
			"Type"=>"password",
	    	"Label"=>"رمز عبور",
	    	"Validation"=>"*",
	    	"Name"=>"Password",	    	
	    	));
    	$f->AddElement(
    		array(
    				"Type"=>"radio",
    		    	"Label"=>"جنسیت",
    		    	"Name"=>"gender",
    		    	"Options"=>array(
    		    			0=>'مرد',
    		    			1=>'زن'),
    		    	"Default"=>"0",	    	
    	));
    	$f->AddElement(
	    	array(
			"Type"=>"text",
	    	"Label"=>"نام",
	    	"Validation"=>"alpha_farsi",
	    	"Name"=>"Firstname",	    	
	    	));
    	$f->AddElement(
	    	array(
			"Type"=>"text",
	    	"Label"=>"نام خانوادگی",
	    	"Validation"=>"alphanumeric_farsi",
	    	"Name"=>"Lastname",	    	
	    	));
    	$f->AddElement(
	    	array(
			"Type"=>"radio",
	    	"Label"=>"نقش",
	    	"Name"=>"Role",
	    	"Vertical"=>true,
	    	"ContainerStyle"=>"text-align:right; margin-right:50px; padding-right:50px; border:1px dotted #aaa;",
	    	"Options"=>ConfigData::$GROUPS,
	    	"Default"=>"Reviewer",	    	
	    	));
    	$f->AddElement(
	    	array(
			"Type"=>"submit",
	    	"Value"=>"ایجاد کاربر",
	    	));
	    	
	    if (count($_POST))
	    {
	    	$r=$f->Validate($_POST,$Errors);
	    	if (!$r)
	    		$Error=$Errors;
	    	else 
	    	{
	    		$r=$this->CreateUser($_POST['Username'],$_POST['Password'],"",$_POST['gender'],$_POST['Firstname'],$_POST['Lastname'],$_POST['Role']);
	    		if ($r)
	    			$Result="با موفقیت ساخته شد.";
	    		else 
	    			$Error[]="کاربر موجود است";
	    	}
	    	
	    }
	    if(count($Error))$this->Result=false;
	    $this->Error=$Error;
	    $this->Result=$Result;
    	$this->Autoform=$f;
    	return $this->Present();
    }
    function CreateUser($Username,$Password,$Email,$Gender=0,$Firstname,$Lastname,$Role="Reviewer")
	{
		if ("Reviewer"==$Role)
			$isReviewer=1;
		else
			$isReviewer=0;
		
		if (ORM::Find(new MyUser(),"Username",$Username))
			return false;
		$Group=ORM::Find1(new MyGroup(), "Title",$Role);
		if(!$Group)
		{
			$res=ORM::Query("MyGroup")->Add($Role,$Role);
			if(is_string($res))
			{
				return false;
			}
			else 
			$Group=$res;
		}
		$U=new MyUser($Username,$Password,$Gender,$Firstname,$Lastname,$isReviewer,"",$Group);
		ORM::Write($U);
		if ($U->ID())
		{
			j::$RBAC->User_AssignRole("Review_".$Role,$U->ID());
			return $U->ID();
		}
		else
		return false;
	}
}
?>