<?php
class TypistTemplateListController extends JControl
{
	function Start()
	{
		$AllTemplates= ORM::Query(new Template())->GetAll();
		$al=new AutolistPlugin($AllTemplates,null,"Select");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->ObjectAccess=true;
		$al->SetHeader('Title', 'عنوان',true);
		$al->SetHeader('EditButton', 'ویرایش',true);
		$al->SetFilter(array($this,"myfilter"));
		$this->FileAutoList=$al;
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	
	function myfilter($k,$v,$D)
	{
		if($k=='CreateTimestamp')
		{
			$c=new CalendarPlugin();
			return "<span dir='ltr'>{$D->CreateTimestamp}</span>";
		}
		elseif($k=='EditButton')
		{
			return "<a class='link_but' href='./Edit?ID={$D->ID()}'>ویرایش</a>";
		}
		else
		{
			return $v;
		}
	}
	
}