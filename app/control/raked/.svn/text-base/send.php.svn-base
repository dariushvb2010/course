<?php
class RakedSendController extends JControl

{
	function Start()
	{
		j::Enforce("Raked");
		
		if (count($_POST))
		{
			$Cotag=$_POST['Cotag']*1;
			$Mailnum=$_POST['Mailnum'];
			$Requester=$_POST['Requester'];
			$Requester=ORM::Find(new ReviewTopic(), $Requester)->Topic();
			$Comment=$_POST['Comment'];
			$Res=ORM::Query(new ReviewProgressSendfile)->AddToFile($Cotag,$Requester,$Mailnum,$Comment,"Raked");
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
		$topics=ReviewTopic::Topics("SendRaked");
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
			"Label"=>"به",
			"Width"=>"150px",
		));
		
		$f->AddElement(array(
			"Type"=>"textarea",
			"Name"=>"Comment",
			"Label"=>"توضیحات"
		));
		$f->AddElement(array(
			"Type"=>"submit",
			"Value"=>"ارسال"
		));
		$this->Form=$f;
	}
}