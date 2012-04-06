<?php
class CorrespondenceTopicController extends JControl
{
	function Start()
	{
		j::Enforce("Correspondence");
		
		//add topic
		if(isset($_POST['Topic']))
		{
			$r=ORM::Query(new ReviewCorrespondenceTopic())->Add($_POST['Topic'],$_POST['Comment']);
			if(is_string($r))
				$Error[]=$r;
		}
		if (count($_POST['item']) && isset($_POST['item']))
		{
			foreach($_POST['item'] as $key=>$value)
			{
				$t=ORM::Find('ReviewCorrespondenceTopic', $value);
				if($t)
				$mytopics[]=$t;
			}
			$this->Topics=$mytopics;
			if (!$this->Topics or count($this->Topics)==0)
			{
				$Error[]="هیچ موردی انتخاب نشده!";
			}
			else
			{
				foreach ($this->Topics as $T)
				{
					if(count($T->Correspondence())==0)
					{
						$Result=ORM::Delete($T);
					}
					else
					{
						$Error[]="عنوان "."<b>".$T->Topic()."</b>"." در مکاتبات استفاده شده است و قابل حذف نیست.";
					}
				}
				ORM::Flush();
				
			}
		}

		//listing items
		{

			$topics=ReviewCorrespondenceTopic::Topics();
			if(count($topics))
			{
				foreach($topics as $key=>$value)
				{
					$topics[$key]['Select']=$topics[$key]['ID'];
				}
				$this->Topics=$topics;
			}
			else
			{
				$Error[]="هیچ عنوانی موجود نیست.";
			}
			if(count($topics))
			{
				$al=new AutolistPlugin($this->Topics,null,"Select");
				//$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
				$al->SetHeader('Select', 'انتخاب',true);
				$al->SetHeader('ID', 'شماره');
				$al->SetHeader('Topic', 'عنوان',true);
				$al->SetHeader('Comment', 'توضیحات',true);
				$al->SetFilter(array($this,"myfilter"));
				$this->TopicList=$al;
			}
			else 
			{
				$al=new AutolistPlugin($topics,null,"Select");
				//$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
				$al->SetHeader('Select', 'انتخاب',true);
				$al->SetHeader('ID', 'شماره');
				$al->SetHeader('Topic', 'عنوان',true);
				$al->SetHeader('Comment', 'توضیحات',true);
				$al->SetFilter(array($this,"myfilter"));
				$this->TopicList=$al;
			}
		}
		
		$this->Error=$Error;
		if (count($Error))
		$this->Result=false;
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

}