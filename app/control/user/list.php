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
			$Cotag=b::CotagFilter($_POST['Cotag']);
		}
		
		$AllUsers= ORM::Query(new MyUser())->GetAll();
		$al=new AutolistPlugin($AllUsers,null,"Select");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->ObjectAccess=true;
		$al->SetHeader('Firstname', 'نام',true);
		$al->SetHeader('Lastname', 'نام خانوادگی',true);
		$al->SetHeader('Codemelli', 'کد ملی',true);
		$al->SetHeader('CreateTimestamp', 'تاریخ ثبت',true);
		$al->SetHeader('EditButton', 'ویرایش',true);
		$al->SetHeader('ChPassButton', 'تغییر رمز',true);
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
			return "<span dir='ltr'>{$c->JalaliFromTimestamp($D->CreateTimestamp)}</span>";
		}
		elseif($k=='EditButton')
		{
			return "<a class='link_but' href='./edit?ID={$D->ID()}'>ویرایش</a>";
		}
		elseif($k=='ChPassButton')
		{
			return "<a class='link_but' href='./resetpass?ID={$D->ID()}'>تغییر رمز</a>";
		}
		else
		{
			return $v;
		}
	}
	
}