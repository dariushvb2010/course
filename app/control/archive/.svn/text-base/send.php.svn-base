<?php
class ArchiveSendController extends JControl

{
	function Start()
	{
//		j::Enforce("Archive");
		
		if (count($_POST))
		{
			$Cotag=$_POST['Cotag']*1;
			$Mailnum=$_POST['Mailnum'];
			$Requester=$_POST['Requester'];
			if(is_numeric($Requester))
				$Requester=ORM::Find(new ReviewTopic(), $Requester)->Topic();
				
			if($_GET['to']=="rajaie")
			{
				$Requester.=" گمرک شهید رجایی";
			}
			elseif($_GET['to']=="iran")
			{
				$Requester.=" گمرک ایران";
				
			}
			if($_GET['from']=="Raked")
			{
				$Operator="Raked";
			}
			else
			{
				$Operator="Archive";
			}
			
			$Comment=$_POST['Comment'];
			$ala=array();
			foreach($_POST['item'] as $key=>$value)
			{
				$Res=ORM::Query(new ReviewProgressSendfile)->AddToFile($value,$Requester,$Mailnum,$Comment,$Operator);
				if(is_string($Res))
				{
					$Error[]=$Res;
				}
				else 
				{
					$ala[]=array(
										 'Cotag'=>$value,
										 'Requester'=>$Requester,
										'Mailnum'=>$Mailnum,
										 'Comment'=>$Comment
											);
					
				}
				
			}
			ORM::Flush();
			$al2=new AutolistPlugin($ala,array(
				'Cotag'=>'کوتاژ',
				'Requester'=>'مقصد',
				'Mailnum'=>'شماره نامه',
				 'Comment'=>'توضیحات'			
				));
				$al2->SetFilter(array($this,'myfilter2'));
				$this->ResultList=$al2;
		}
		else //listing items
		{
			$topics=ReviewTopic::Topics($_GET['to']);
			foreach($topics as $v)
			{
				$options[$v['ID']]=$v['Topic'];
				
			}
			$this->Options=$options;
			$al=new AutolistPlugin(null,null,"Select");
			$al->SetHeader('Select', 'انتخاب',true);
			$al->SetHeader('Cotag', 'کوتاژ');
			//$al->SetFilter(array($this,"myfilter"));
			$this->AssignList=$al;
		}
		$this->makeForm();
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	function myfilter2($k,$v,$o)
	{
		if($v==null)
		{
			return '-';
		}
		else
		{
			return $v;
		}
	}
	function makeForm()
	{
		$topics=ReviewTopic::Topics($_GET['to']);
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