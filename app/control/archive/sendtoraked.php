<?php
use Doctrine\Common\Collections\ArrayCollection;
class ArchiveSendtorakedController extends JControl
{
	function Start()
	{
		j::Enforce("Archive");
		if (count($_POST['item']))
		{
			foreach($_POST['item'] as $key=>$value)
			{
				$t=ORM::Find('ReviewFile', $value);
				if($t)
				$myfiles[]=$t;
			}
			$this->Files=$myfiles;

			//---------not selected-----------------
			if (!$this->Files or count($this->Files)==0)
			{
				$Error[]="هیچ موردی انتخاب نشده!";
			}
			else
			{
				$Sender=ORM::Find(new ReviewTopic,"Type","archive");
				$Receiver=ORM::Find(new ReviewTopic,"Type","raked");
				//-----------send---------------------
				if(isset($_POST['send']))
				{
					if(isset($_GET['ID']))
					{
						//ORM::Find($Classname, $QueryArguments);
					}
					else
					{
						if($Sender && $Receiver)
						{
							//new mail with state 1 (send)
							$mail=new ReviewMail($Sender[0], $Receiver[0],$_GET['MailNum'],1);
							ORM::Persist($mail);
						}
					}
				}
				//--------------save--------------------
				else if(isset($_POST['save']))
				{
					if(isset($_GET['ID']))
					{

					}
					else
					{


						if($Sender && $Receiver)
						{
							//new mail with state 0
							$mail=new ReviewMail($Sender[0], $Receiver[0],$_GET['MailNum']);
							ORM::Persist($mail);
						}
					}
				}
				$flag=true;
				if($mail)
				{
					foreach ($this->Files as $F)
					{

						$FinishResult=ORM::Query(new ReviewProgressPost)->AddToFile($F,$mail,1);
						if(is_string($FinishResult))
						{
							$Error[]=$FinishResult;
						}
						else
						{
							$flag=false;
							$ala[]=array('ID'=>$F->ID(),
									 'Cotag'=>$F->Cotag(),
							);
						}
					}
				}
				if($flag && $mail)
				ORM::Delete($mail);
				ORM::Flush();
				$al=new AutolistPlugin($ala,null,"Select",true,"ردیف");
				$al->SetHeader('Cotag', 'کوتاژ');
				$al->Width="80%";
				$al->InputValues['ColsCount']=5;
				$al->InputValues['RowsCount']=30;
				$al->_LimitAttr=array("title"=>"اگر قسمت تعداد را صفر قرار دهید تمام کوتاژ ها را می توانید ببینید");
				$al->_TierAttr=array("style"=>"font-size:11pt;");//
				$this->ResultList=$al;
			}
		}
		else if(count($_GET['MailNum']))//listing items
		{

			$Finishable=ORM::Query(new ReviewFile)->FinishableFiles($_POST['off'],$_POST['lim'],$_POST['sort'],$_POST['ord']);
			if(count($Finishable))
			{
				foreach($Finishable as $key=>$value)
				{
					$Finishable[$key]['Select']=$Finishable[$key]['ID'];
				}
				$this->Finishable=$Finishable;
			}
			else
			{
				$Error[]="اظهارنامه قابل ارسال بایگانی راکد وجود ندارد.";
			}
			$al=new AutolistPlugin($this->Finishable,null,"Select");
			$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
			$al->HasTier=true;
			$al->TierLabel="ردیف";
			$al->SetHeader('Select', 'انتخاب',true);
			$al->SetHeader('Cotag', 'کوتاژ');
			$al->SetHeader('CreateTimestamp', 'زمان وصول',true);
			$al->SetFilter(array($this,"myfilter"));
			$this->List=$al;
		}
		else if(isset($_GET["ID"]))
		{

			$Mail=ORM::Find("ReviewMail",$_GET['ID']);
			
			$Save=array();
			if($Mail)
			{
				$Posts=$Mail->Post();
				foreach($Posts as $i=>$p)
				{
					$Save[]=$p->File()->ID();
				}
				$this->Save=$Save;
				//ORM::Dump($Save);
			}
			else 
			{
	 			//ERROR
				return ;
			}
			$Finishable=ORM::Query(new ReviewFile)->FinishableFiles($_POST['off'],$_POST['lim'],$_POST['sort'],$_POST['ord']);
			if(count($Finishable))
			{
				foreach($Finishable as $key=>$value)
				{
					$Finishable[$key]['Select']=$Finishable[$key]['ID'];
				}
				$this->Finishable=$Finishable;
			}
			else
			{
				$Error[]="اظهارنامه قابل ارسال بایگانی راکد وجود ندارد.";
			}
			$al=new AutolistPlugin($this->Finishable,null,"Select");
			$al->HasTier=true;
			$al->Width="auto";
			$al->TierLabel="ردیف";
			$al->SetHeader('Select', 'انتخاب',true);
			$al->SetHeader('Cotag', 'کوتاژ');
			$al->SetFilter(array($this,"myfilter2"));
			$this->List=$al;
		}
		else
		{
			$Mails=ORM::Query("ReviewMail")->GetMails(ReviewTopic::Archive(),ReviewTopic::Raked(),0);
			$this->Mails=$Mails;
		}
		$this->Error=$Error;
		if (count($Error))
		$this->Result=false;
		$this->Header="فرستادن اظهارنامه ها به بایگانی راکد";
		$this->ButtonValue="ارسال";
		return $this->Present();
	}


	function myfilter2($k,$v,$D)
	{
	//	var_dump($D);
		if($k=='CreateTimestamp')
		{
			$c=new CalendarPlugin();
			return "<span dir='ltr'>".$c->JalaliFullTime($v)."</span>";
		}
		elseif($k=='Select' && array_search($D['ID'],$this->Save)!==false)
		{
			
			return "<input type='checkbox' class='item' value='".$D['ID']."' name='item[]' checked='checked' />";
		}
		elseif($k=='Select')
		{
			return "<input type='checkbox' class='item' value='".$D['ID']."' name='item[]' />";
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
			return "<span dir='ltr'>".$c->JalaliFullTime($v)."</span>";
		}
		elseif($k=='Select')
		{
			return "<input type='checkbox' class='item' value='".$D['ID']."' name='item[]' />";
		}

		else
		{
			return $v;
		}
	}

}