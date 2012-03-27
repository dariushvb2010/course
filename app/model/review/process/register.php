<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessRegisterRepository")
 * */
class ReviewProcessRegister extends ReviewProgress
{
	
	private function GenerateClass($Pre)
	{
		
		$Class=b::
		$Class=ORM::Query($this)->GetMaxClass($Pre);
		$Class++;
		return $Class;
	}
	function __construct(ReviewFile $File=null,MyUser $User=null)
	{
		parent::__construct($File,$User);
		if($File)
		{
			if($File->LLP("Review"))
			{
				$Pre=$File->LLP("Review")->Provision();
				$Classe=b::GenerateClassNum($Pre);
				$File->SetClass($Classe);
				
			}
			
		}
	}
	
	function  Summary()
	{
		$str="پرونده به شماره کوتاژ ".$this->File()->Cotag()." در مکاتبات با شماره کلاسه ".$this->File()->GetClass().'ثبت گردید .';
		return $str;
	}
	function Title()
	{
		return "ثبت کلاسه";
	}
	function Event()
	{
		return "ProcessRegister";
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessRegisterRepository extends EntityRepository
{
	/**
	 * 
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File=null)
	{
		$CurrentUser=MyUser::CurrentUser();
		if($File)
		{
			if(FileFsm::IsPossible($File->State(),"ProcessRegister"))
			{
				$R=new ReviewProcessRegister($File, $CurrentUser);
				$R->SetState($File,FileFsm::NextState($File->State(),"ProcessRegister"));
				ORM::Persist($File);
				ORM::Write($R);
				$res['Class']=$R;
			}
			else
			{
				$res['Error']=" پرونده با شماره کلاسه ".$File->GetClass()."در مرحله ای نیست که بتوان ثبت کلاسه کرد.";
			}
		}
		else 
		{
			$res['Error']="اظهارنامه وجود ندارد.";	
		}
		return $res;
	}
	public function GetMaxClass()
	{
		$r=j::DQL("SELECT MAX(F.Class) AS Result FROM ReviewFile AS F");
		return $r[0]['Result'];
	}
}