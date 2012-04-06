<?php
class ManagerBazbinsBazbinController extends JControl
{
	function Start()
	{
		j::Enforce("MasterHand");
		if($_GET['id']){
			$my_reviewer=ORM::Query("MyUser")->find($_GET['id']);
			if(isset($_POST['State']))
			{
				$s=$_POST['State']*1;
				if($s==2)
					$my_reviewer->Disable();
				else 
				{
					$my_reviewer->Enable();
					$my_reviewer->SetState($s);
				}
				ORM::Persist($my_reviewer);
			}
			if(!$my_reviewer){
				$Error[]='چنین کارشناسی موجود نیست.';
			}else{
				$this->ID=$_GET['id'];
				$this->State=$my_reviewer->Enabled();
				$this->CotagCount=ORM::Query(new MyUser)->AssignedReviewableFileCount($my_reviewer);
				$this->Name=$my_reviewer->getFullName();				
			}
		}		
		
		
		//-------------------------------------list of assigned Files----------------
		if (count($_POST['item']))
		{
			foreach($_POST['item'] as $key=>$value)
			{
				$t=ORM::Find('ReviewFile', $value);
				if($t)
				$myfiles[]=$t;
			}
			$this->Files=$myfiles;
			if (!$this->Files or count($this->Files)==0)
			{
				$Error[]="هیچ موردی انتخاب نشده!";
			}
			else
			{
				foreach ($this->Files as $F)
				{
					$Reviewer=ORM::Query("MyUser")->getRandomReviewer();
					$AssignResult=ORM::Query("ReviewProgressAssign")->AddToFile($F,$Reviewer);
					if(is_string($AssignResult))
					{
						$Error[]=$AssignResult;
					}
				}
				ORM::Flush();
				$al2=new AutolistPlugin($this->Files,array(
							'ID'=>'ردیف',
							'Cotag'=>'کوتاژ',
							'Reviewer'=>'کارشناس انتخاب شده'
				));
				$al2->SetHeader('CreateTime', 'زمان وصول',true);
				$al2->ObjectAccess=true;
				$al2->SetFilter(array($this,'myfilter2'));
				$this->ResultList=$al2;
			}
		}
		elseif($_GET['id']) //listing items
		{
		
			$MyUnreviewedFiles=$this->Count=ORM::Query("MyUser")->AssignedReviewableFile($_GET['id']);
			if($MyUnreviewedFiles){
				$al=new AutolistPlugin($MyUnreviewedFiles,null,"Select");
				$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
				$al->SetHeader('Select', 'انتخاب',true);
				$al->SetHeader('Cotag', 'کوتاژ');
				$al->ObjectAccess=true;
				$al->SetHeader('CreateTimestamp', 'زمان وصول',true);
				$al->SetFilter(array($this,"myfilter"));
		
				$this->FileAutoList=$al;
		
				$this->Count=count($MyUnreviewedFiles);
				$this->MyUnreviewedFiles=$MyUnreviewedFiles;
			}else{
				$Error[]='هیچ اظهارنامه ای نزد کارشناس موجود نیست.';
			}
		}else{
			$Error[]='هیچ کارشناسی انتخاب نشده است.';
		}
		//----------------------------------L O A F-----------------------
		
		$this->Error=$Error;
		if (count($Error))
			$this->Result=false;
		$this->makeForm();
		return $this->Present();
	}
	function makeForm()
	{
		$f=new AutoformPlugin("post");
		$f->AddElement(array(
				"Name"=>"State",
				"Type"=>"select",
				"Options"=>array("0"=>"مرخصی",
								"1"=>"مشغول کار",
								"2"=>"غیرفعال"),
				"Label"=>"وضعیت",
				"Default"=>$this->State,
		));
		$f->AddElement(array(
				"Type"=>"submit",
				"Value"=>"اعمال تغییرات",
		));
		$this->Form=$f;
	}
	function myfilter2($k,$v,$o)
	{
		if($k=='CreateTimestamp')
		{
			$c=new CalendarPlugin();
			return "<span dir='ltr'>".$v."</span>";
		}
		elseif($k=='Reviewer')
		{
			if ($o->LastReviewer())
			return $o->LastReviewer()->getFullName();
			else
			return "";
		}
		else
		{
			return $v;
		}
	}
	function myfilter($k,$v,$D)
	{
		if($k=='CreateTimestamp')
		{
			$c=new CalendarPlugin();
			return "<span dir='ltr'>".$D->CreateTime()."</span>";
		}
		elseif($k=='Select')
		{
			return "<input type='checkbox' class='item' value='".$D->ID()."' name='item[]' />";
		}
		else
		{
			return $v;
		}
	}

}