<?php
class ManagerTopicController extends JControl
{
	function Start()
	{
		j::Enforce("MasterHand");
		//add topic
		if(isset($_POST['Topic']))
		{
			$r=ReviewTopic::Add($_POST['Topic'],$_POST['Comment'],$_POST['Type']);
			if(is_string($r))
				$Error[]=$r;
		}
		if (count($_POST['item']) && isset($_POST['item']))
		{
			foreach($_POST['item'] as $key=>$value)
			{
				$t=ORM::Find('ReviewTopic', $value);
				if($t)
				$mytopics[]=$t;
			}
			
			if (!$mytopics or count($mytopics)==0)
			{
				$Error[]="هیچ موردی انتخاب نشده!";
			}
			else
			{
				foreach ($mytopics as $T)
				{
						$Result=ORM::Delete($T);
				}
				
			}
		}

		ORM::Flush();
		//listing items
		{

			$topics=ReviewTopic::Topics();
			if(count($topics))
			{
				foreach($topics as $key=>$value)
				{
					$topics[$key]['Select']=$topics[$key]['ID'];
				}
				$this->Topics=$topics;
			}
			if(count($topics))
			{
				$al=new AutolistPlugin($this->Topics,null,"Select");
				//$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
				$al->HasTier=true;
				$al->TierLabel="ردیف";
				$al->Width="auto";
				$al->SetHeader('Select', 'انتخاب',true);
				$al->SetHeader('ID', 'شماره');
				$al->SetHeader('Topic', 'عنوان',true);
				$al->SetHeader('Type', 'نوع',true);
				$al->SetHeader('Comment', 'توضیحات',true);
				$al->SetFilter(array($this,"myfilter"));
				$this->TopicList=$al;
			}
		}
		
		$this->Error=$Error;
		if (count($Error))
		$this->Result=false;
		$this->makeForm();
		return $this->Present();
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
	
	function makeForm()
	{
		$f=new AutoformPlugin("post");
		$f->AddElement(array(
				"Type"=>"text",
				"Name"=>"Topic",
				"Label"=>"عنوان",
		));
		$f->AddElement(array(
				"Name"=>"Type",
				"Type"=>"select",
				"Width"=>"150px",
				"Options"=>array(
								"othergates"=>"گمرک های اجرایی",
								"rajaie"=> "بخش های گمرک شهید رجایی",
								"iran"=>"بخش های گمرک ایران",
								"other"=>"سایر(ارسال بایگانی بازبینی)",
								"correspondent"=>"طرف مکاتبه",
								"comment"=>"توضیحات",
								
														),
				"Label"=>"نوع",
		));
		$f->AddElement(array(
				"Type"=>"textarea",
				"Name"=>"Comment",
				"Label"=>"توضیحات",
		));
		$f->AddElement(array(
				"Type"=>"submit",
				"Value"=>"اضافه کردن",
		));
		$this->Form=$f;
	}

}