<?php
class UserEditController extends BaseControllerClass
{
    function Start ()
    {
		j::Enforce("CreateUser");

	    if (count($_POST))
	    {
			$f=$this->makeform();
	    	$r=$f->Validate($_POST,$Errors);
	    	if (!$r)
	    		$Error=$Errors;
	    	else 
	    	{
	    		$r=$this->EditUser($_POST['ID'],"",$_POST['gender'],$_POST['Firstname'],$_POST['Lastname'],$_POST['Codemelli'],$_POST['Role']);
	    		$f=$this->makeform($_POST);
	    		if ($r)
	    			$Result="با موفقیت ثبت شد.";
	    		else 
	    			$Error[]="خطا";
	    	}
	    	
	    }else{
	    	$U=ORM::Find("MyUser", $_GET['ID']);
	    	if (!$U)
	    		$Error[]="کاربر وجود ندارد.";
	    	
	    	$f=$this->makeform($U,true);
	    }
	    if(count($Error))$this->Result=false;
	    $this->Error=$Error;
	    $this->Result=$Result;
    	$this->Autoform=$f;
    	return $this->Present("",'user.create');
    }
    
	
    function EditUser($ID,$Email,$Gender=0,$Firstname,$Lastname,$Codemelli,$Role="Reviewer")
	{
		if ("Reviewer"==$Role)
			$isReviewer=1;
		else
			$isReviewer=0;
		
		$Group=$this->groupAppend($Role);
		
		$U=ORM::Find("MyUser", $ID);
		$U=j::ODQL("SELECT U FROM MyUser U WHERE U.ID=?",$ID);
		if (!$U)
			return false;
		else
			$U=$U[0];

		$U->SetGender($Gender);
		$U->SetFirstname($Firstname);
		$U->SetLastname($Lastname);
		$U->SetGroup($Group);
		$U->SetisReviewer($isReviewer);
		$U->SetCodemelli($Codemelli);
		
		
		j::$RBAC->User_UnassignAllRoles($U->ID());
		j::$RBAC->User_AssignRole("Review_".$Role,$U->ID());
		ORM::Write($U);
		return true;
	}
	
	function makeform($Data=null,$isOBJ=false){
		$f=new AutoformPlugin("post");
		$f->AddElement(
				array(
						"Type"=>"hidden",
						"Name"=>"ID",
						"Value"=>($isOBJ?$Data->ID():$Data['ID']),
				));
		$f->AddElement(
				array(
						"Type"=>"radio",
						"Label"=>"جنسیت",
						"Name"=>"gender",
						"Options"=>array(
								0=>'مرد',
								1=>'زن'),
						"Default"=>($isOBJ?$Data->Gender():$Data['gender']),
				));
		$f->AddElement(
				array(
						"Type"=>"text",
						"Label"=>"نام",
						"Validation"=>"alpha_farsi",
						"Name"=>"Firstname",
						"Value"=>($isOBJ?$Data->Firstname():$Data['Firstname']),
				));
		$f->AddElement(
				array(
						"Type"=>"text",
						"Label"=>"نام خانوادگی",
						"Validation"=>"alphanumeric_farsi",
						"Name"=>"Lastname",
						"Value"=>($isOBJ?$Data->Lastname():$Data['Lastname']),
				));
		$f->AddElement(
				array(
						"Type"=>"text",
						"Label"=>"کد ملی",
						"Validation"=>"alphanumeric_farsi",
						"Name"=>"Codemelli",
						"Value"=>($isOBJ?$Data->Codemelli():$Data['Codemelli']),
				));
		$f->AddElement(
				array(
						"Type"=>"radio",
						"Label"=>"نقش",
						"Name"=>"Role",
						"Vertical"=>true,
						"ContainerStyle"=>"text-align:right; margin-right:50px; padding-right:50px; border:1px dotted #aaa;",
						"Options"=>ConfigData::$GROUPS,
						"Default"=>($isOBJ?$this->GetGroupTitleByID($Data->Group()):$Data['Role']),
				));
		$f->AddElement(
				array(
						"Type"=>"submit",
						"Value"=>"ثبت تغییرات",
				));
		return $f;
	}
	
	function GetGroupTitleByID($ID){
		$U=j::ODQL("SELECT G FROM MyGroup G WHERE G.ID=?",$ID);
		$U=$U[0];
		return $U->Title();
	}
	
	function groupAppend($Role){
		$Group=ORM::Find1("MyGroup", "Title",$Role);
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
		return $Group;
	}
}
?>
