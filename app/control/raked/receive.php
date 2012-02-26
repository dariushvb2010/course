<?php
class RakedReceiveController extends JControl

{
	function Start()
	{
		j::Enforce("Raked");
		
		if(count($_POST['item']))
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
				
				$mail=ORM::Find("ReviewMail",$_GET['ID']);
				if($mail)
				foreach ($this->Files as $F)
				{
					
					$Result=ORM::Query(new ReviewProgressPost)->AddToFile($F,$mail,0,1);
					if(is_string($Result))
					{
						$Error[]=$Result;
						

					}
					else
					{
						$ala[]=array('ID'=>$F->ID(),
									 'Cotag'=>$F->Cotag(),
						);
					}
				}
				ORM::Flush();
				$al2=new AutolistPlugin($ala,array(
						
						'Cotag'=>'کوتاژ',
				));
				$al2->HasTier=true;
				$al2->Width="auto";
				$al2->TierLabel="ردیف";
				$this->ResultList=$al2;
			}
		}
		elseif(isset($_GET["ID"]))
		{
			$Mail=ORM::Find("ReviewMail",$_GET['ID']);
			if($Mail)
			{
				$this->Posts=$Mail->Post();
				foreach($this->Posts as $i=>$p)
				{
					if($p->File()->LLP() instanceof ReviewProgressPost)
					if($p->File()->LLP()->IsSend()==1)
					{
						$Cotages[$i]["Cotag"]= $p->File()->Cotag();
						$Cotages[$i]["Select"]=$p->File()->ID();
						$Cotages[$i]["ID"]=$p->File()->ID();
					}

				}
				$this->Cotag=$Cotages;
			}
			$al=new AutolistPlugin($this->Cotag,null,"Select");
			$al->HasTier=true;
			$al->Width="auto";
			$al->TierLabel="ردیف";
			$al->SetHeader('Select', 'انتخاب',true);
			$al->SetHeader('Cotag', 'کوتاژ');
			$al->SetFilter(array($this,"myfilter"));
			$this->List=$al;
		}
		else
		{
			$Mails=ORM::Query("ReviewMail")->GetMails(ReviewTopic::Archive(),ReviewTopic::Raked(),1);
			//ORM::Dump($Mails);
			$this->Mails=$Mails;
		}
		$this->Error=$Error;
		if (count($Error))
		$this->Result=false;
		return $this->Present(null,"receive");
	}
	function myfilter($k,$v,$D)
	{
		if($k=='Select')
		{
			return "<input type='checkbox' class='item' value='".$D['ID']."' name='item[]' />";
		}
		else
		{
			return $v;
		}
	}
	/*
		if (count($_POST))
		{
		$Cotag=$_POST['Cotag']*1;
		$Mailnum=$_POST['Mailnum'];
		$Requester=$_POST['Requester'];
		$Requester=ORM::Find(new ReviewTopic(), $Requester)->Topic();
		$Comment=$_POST['Comment'];
		$Res=ORM::Query(new ReviewProgressReceivefile)->AddToFile($Cotag,$Requester,$Mailnum,$Comment,"Raked");
		if(is_string($Res))
		{
		$Error[]=$Res;
		}
		else
		$this->Result="مکاتبه با موفقیت ثبت شد.";
		}
		$this->makeForm();
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
		}
		function makeForm()
		{
		$topics=ReviewTopic::Topics("ReceiveRaked");
		foreach($topics as $v)
		{
		$ts[$v['ID']]=$v['Topic'];
		}
		$f=new AutoformPlugin("post");
		$f->AddElement(array(
		"Type"=>"text",
		"Name"=>"Cotag",
		"Label"=>"کوتاژ",
		"Validation"=>"number",
		));
		$f->AddElement(array(
		"Type"=>"text",
		"Name"=>"Mailnum",
		"Label"=>"شماره نامه",
		));
		$f->AddElement(array(
		"Type"=>"select",
		"Name"=>"Requester",
		"Options"=>$ts,
		"Label"=>"از",
		"Width"=>"150px",
		));

		$f->AddElement(array(
		"Type"=>"textarea",
		"Name"=>"Comment",
		"Label"=>"توضیحات"
		));
		$f->AddElement(array(
		"Type"=>"submit",
		"Value"=>"دریافت"
		));
		$this->Form=$f;
		}*/
}