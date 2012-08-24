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
	function __construct(ReviewFile $File=null,MyUser $User=null, $IfPersist=true)
	{
		parent::__construct($File,$User,$IfPersist);
		if($File)
		{
			if($File->LLP("Review"))
			{
				$Pre=$File->LLP("Review")->Provision();
				$Classe=b::GenerateClassNum($Pre);
				if($Classe==0){
					$this->error='مشکلی در تخصیص شماره کلاسه پیش آمده است.';
					return false;
				}
				if($IfPersist)
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
			$R=new ReviewProcessRegister($File, $CurrentUser,false);
			$err=$R->Check();
			if(!is_string($err)){
				$R=new ReviewProcessRegister($File, $CurrentUser,true);
				$R->Apply();
				ORM::Persist($R);
				ORM::Persist($File);
				$res['Class']=$File->GetClass();
			}else{
				//ORM::Clear();
				$res['Error']=$err;
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