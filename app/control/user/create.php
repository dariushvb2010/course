<?php
class UserCreateController extends BaseControllerClass
{
    function Start ()
    {
		//j::Enforce("CreateUser");
		
		$f=$this->makeform();
	    if (count($_POST))
	    {
	    	$r=$f->Validate($_POST,$Errors);
	    	if (!$r)
	    		$Error=$Errors;
	    	else 
	    	{
	   			$user=$this->CreateUser($_POST['Username'],$_POST['Password'],$_POST['Email'],$_POST['gender'],$_POST['Firstname'],$_POST['Lastname'],$_POST['Codemelli'],$_POST['Role'], $_POST['SaleVorod']);
	    		
	    		if ($user)
	    		{
	    			$ch1 = $_POST['Choice1']*1;
	    			$ch2 = $_POST['Choice2']*1;
	    			$ch3 = $_POST['Choice3']*1;
	    			$choice1 = ORM::Find('CourseChoice', $ch1);
	    			$choice2 = ORM::Find('CourseChoice', $ch2);
	    			$choice3 = ORM::Find('CourseChoice', $ch3);
	    			if($choice1 and $choice2 and $choice3)
	    			{
	    				$poll1 = ORM::Query('CoursePoll')->Add($user, $choice1, CoursePoll::Score_high);
	    				$poll2 = ORM::Query('CoursePoll')->Add($user, $choice2, CoursePoll::Score_medium);
	    				$poll3 = ORM::Query('CoursePoll')->Add($user, $choice3, CoursePoll::Score_low);
	    				$Result = 'ثبت نام انجام شد. شما می توانید اولویت ها را بعد از ورود به سیستم در منوی پروفایل من تغییر دهید.';
	    			}
	    			else
	    				$Error[]="تمام اولویت ها را انتخاب نمایید!";
	    		}
	    		else 
	    			$Error[]="ثبت نام انجام نشد. کاربر موجود است!";
	    	}
	    	
	    }
	    if(count($Error))$this->Result=false;
	    $this->Error=$Error;
	    $this->Result=$Result;
    	$this->Autoform=$f;
    	return $this->Present();
    }
    function CreateUser($Username,$Password,$Email,$Gender=0,$Firstname,$Lastname,$Codemelli,$Role='free', $SaleVorod)
	{
		
		if (ORM::Find("MyUser","Username",$Username))
			return false;
		if(empty($Role))
			$Role = 'free';
		$Group=$this->groupAppend($Role);
		if(!$Group)
		{
			return false;
		}
		$U=new MyUser($Username,$Password,$Gender,$Firstname,$Lastname,$Codemelli,$isReviewer,$Email,$Group, $SaleVorod);
		
		ORM::Write($U);
		if ($U->ID())
		{
			j::$RBAC->User_AssignRole("Review_".$Role,$U->ID());
			
			return $U;
		}
		else
			return false;
	}
    
	
    function makeform(){
		$f=new AutoformPlugin("post");
		$f->AddElement(
				array(
						"Type"=>"text",
						"Label"=>"نام کاربری",
						"Validation"=>"/^[a-zA-Z0-9]{3,}$/",
						"Name"=>"Username",
						'Value'=>$_POST['Username']
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
						'Value'=>$_POST['Firstname']
				));
		$f->AddElement(
				array(
						"Type"=>"text",
						"Label"=>"نام خانوادگی",
						"Validation"=>"alphanumeric_farsi",
						"Name"=>"Lastname",
						'Value'=>$_POST['Lastname']
				));
		$f->AddElement(
				array(
						"Type"=>"text",
						"Label"=>"شماره دانشجویی",
						"Validation"=>"/^\d{8}$/",
						"Name"=>"Codemelli",
						'Value'=>$_POST['Codemelli']
				));
		$f->AddElement(
				array(
						"Type"=>"text",
						"Label"=>"سال ورود",
						"Name"=>"SaleVorod",
						'Value'=>$_POST['SaleVorod']
				));
		$f->AddElement(
				array(
						"Type"=>"text",
						"Label"=>"پست الکترونیک",
						"Validation"=>"/^.+?@.+?$/",
						"Name"=>"Email",
						'Value'=>$_POST['Email']
				));
		
		$f->AddElement(
				array(
						"Type"=>"select",
						"Label"=>'اولویت اول',
						"Name"=>'Choice1',
						"ContainerStyle"=>"text-align:right; margin-right:50px; padding-right:50px; border:1px dotted #aaa;",
						"Options"=>$this->Choices(),
						"Default"=>$_POST['Choice1']
				));
		$f->AddElement(
				array(
						"Type"=>"select",
						"Label"=>'اولویت دوم',
						"Name"=>'Choice2',
						"ContainerStyle"=>"text-align:right; margin-right:50px; padding-right:50px; border:1px dotted #aaa;",
						"Options"=>$this->Choices(),
						'Default'=>$_POST['Choice2']
				));
		$f->AddElement(
				array(
						"Type"=>"select",
						"Label"=>'اولویت سوم',
						"Name"=>"Choice3",
						"ContainerStyle"=>"text-align:right; margin-right:50px; padding-right:50px; border:1px dotted #aaa;",
						"Options"=>$this->Choices(),
						'Default'=>$_POST['Choice3']
				));
		$f->AddElement(
				array(
						"Type"=>"submit",
						"Value"=>"ثبت نام",
				));
		return $f;
	}
	
	function Choices(){
		$choices = ORM::Query('CourseChoice')->GetAll();
		foreach($choices as $ch){
			$res[$ch->ID()] = $ch->Title();
		}
		return $res;
	}
	
	
	function groupAppend($Role){
		$Group=ORM::Find1("MyGroup", "Title",$Role);
		if(!$Group)
		{
			$persianTitle = (isset(ConfigData::$GROUPS[$Role]) ? ConfigData::$GROUPS[$Role] : $Role);
			$res=ORM::Query("MyGroup")->Add($Role,$persianTitle);
			if(is_string($res))
			{
				return false;
			}
			else
				$Group=$res;
		}
		return $Group;
	}
}
?>
