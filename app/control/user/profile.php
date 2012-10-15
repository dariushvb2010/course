<?php
class UserProfileController extends BaseControllerClass
{
    function Start ()
    {
    	$user=MyUser::CurrentUser();
		if(!$user)
			die();
		
		$f=$this->makeform();
	    if (count($_POST))
	    {
	    	$r=$f->Validate($_POST,$Errors);
	    	if (!$r)
	    		$Error=$Errors;
	    	else 
	    	{
	   			
	    		
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
	    				$oldPoll1 = ORM::Query('CoursePoll')->GetByUserAndScore($user,CoursePoll::Score_high);
	    				$oldPoll2 = ORM::Query('CoursePoll')->GetByUserAndScore($user,CoursePoll::Score_medium);
	    				$oldPoll3 = ORM::Query('CoursePoll')->GetByUserAndScore($user,CoursePoll::Score_low);
	    				if($oldPoll1 and $oldPoll2 and $oldPoll3){
	    					$oldPoll1[0]->SetCourseChoice($choice1);
	    					$oldPoll2[0]->SetCourseChoice($choice2);
	    					$oldPoll3[0]->SetCourseChoice($choice3);
	    					ORM::Flush();
	    					$f = $this->makeform();
	    					$Result = 'اولویت ها تغییر یافت.';
	    				}else 
	    					$Error[] = 'شما موقع ثبت نام اولویت انتخاب نکرده اید!';
	    			}
	    			else
	    				$Error[]="تمام اولویت ها را انتخاب نمایید!";
	    		}
	    		else 
	    			$Error[]='کاربر موجود نیست';
	    	}
	    	
	    }
	    if(count($Error))$this->Result=false;
	    $this->Error=$Error;
	    $this->Result=$Result;
    	$this->Autoform=$f;
    	$this->User = MyUser::CurrentUser();
    	return $this->Present();
    }
    
	
    function makeform(){
		$f=new AutoformPlugin("post");
		$f->AddElement(
				array(
						"Type"=>"select",
						"Label"=>'اولویت اول',
						"Name"=>'Choice1',
						"ContainerStyle"=>"text-align:right; margin-right:50px; padding-right:50px; border:1px dotted #aaa;",
						"Options"=>$this->Choices(),
						'Default'=>$this->MyChoiceID(1)
				));
		$f->AddElement(
				array(
						"Type"=>"select",
						"Label"=>'اولویت دوم',
						"Name"=>'Choice2',
						"ContainerStyle"=>"text-align:right; margin-right:50px; padding-right:50px; border:1px dotted #aaa;",
						"Options"=>$this->Choices(),
						'Default'=>$this->MyChoiceID(2)
				));
		$f->AddElement(
				array(
						"Type"=>"select",
						"Label"=>'اولویت سوم',
						"Name"=>"Choice3",
						"ContainerStyle"=>"text-align:right; margin-right:50px; padding-right:50px; border:1px dotted #aaa;",
						"Options"=>$this->Choices(),
						'Default'=>$this->MyChoiceID(3)
				));
		$f->AddElement(
				array(
						"Type"=>"submit",
						"Value"=>"تغییر اولویت ها",
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
	/**
	 * @example 
	 * @param integer $num like 1, 2, 3
	 */
	function MyChoiceID($num){
		$user = MyUser::CurrentUser();
		$poll = ORM::Query('CoursePoll')->GetByUserAndScore($user,$num);
		if($poll){
			$poll = $poll[0];
			return $poll->CourseChoice()->ID();
		}
	}
	
	
}
?>
