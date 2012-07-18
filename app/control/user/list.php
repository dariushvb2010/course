<?php
class UserListController extends JControl
{
	function Start()
	{
		j::Enforce("Reviewer");
		if($_REQUEST['Class'])
		{
			$Class=$_POST['Classe']*1;
			$File=ORM::Query(new ReviewFile())->GetRecentFileByClasse($Class);
			$Cotag=$File->Cotag();	
		}
		else if ($_REQUEST['Cotag'])
		{
			$Cotag=$_POST['Cotag']*1;
		}
		
		$AllUsers= ORM::Query(new MyUser())->GetAll();
		$al=new AutolistPlugin($AllUsers,null,"Select");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->SetHeader('Firstname', 'نام',true);
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->ObjectAccess=true;
		$al->SetHeader('Lastname', 'نام خانوادگی',true);
		$al->SetHeader('CreateTimestamp', 'تاریخ ثبت',true);
		$al->SetHeader('EditButton', 'ویرایش',true);
		$al->SetFilter(array($this,"myfilter"));
		$this->FileAutoList=$al;
		
		$this->Count=count($AllUsers);
		$this->MyUnreviewedFiles=$AllUsers;
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