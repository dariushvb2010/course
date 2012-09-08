<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressClasseconfirmRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressClasseconfirm extends ReviewProgress
{
	/**
	* @Column(type="boolean")
	* @var boolean
	*/
	protected  $Confirm;
	function Confirm()
	{
		return $this->Confirm;
	}
	function setConfirm($value)
	{
		$this->Confirm=$value;
	}
	
	function __construct(ReviewFile $File=null,MyUser $user=null)
	{
		parent::__construct($File,$user);
	}
	
	function  Summary()
	{
		if($this->User())
			if($Confirm){
				return "اعلام اشکال اظهارنامه توسط مدیر تایید گردید و به مکاتبات ارسال شد. ";
			}else{
				return "اعلام اشکال اظهارنامه توسط مدیر تایید نگردید و به کارشناس جهت بازبینی مجدد مرجوع شد. ".BR.$this->Comment;
			}
		else 
			return "خطا در گزارش گیری";
	}
	
	function Title()
	{
		return "تایید مسئول بازبینی";
	}
	function Manner(){
		return "";
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressClasseconfirmRepository extends EntityRepository
{
	/**
	 * 
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile($cotag,$Confirmation,$Comment=null)
	{
		$CurrentUser=MyUser::CurrentUser();
		
		$file = b::GetFile($cotag);
		if($cotag==null OR $file==null)
		{
			$Error=v::Ecnf();
			return $Error;
		}
		
		if(!$this->ValidateFile($file))return false;
		
		$R=new ReviewProgressClasseconfirm($file,$CurrentUser);
		$R->setConfirm($Confirmation);
		
		if(!$Confirmation){
			if (strlen($Comment)<15)
			{
				$Error="حداقل توضیحی به طول 10 حرف نیاز دارد.";
				return $Error;
			}else{
				$R->setComment($Comment);
			}
		}
		
		ORM::Persist($R);
		
		return $R;
		
	}
	
	/**
	 * 
	 * Validates IF Cotag can have this Progress in current state
	 * @param string $Cotag
	 */
	 function ValidateFile(ReviewFile $file){
	 	$lastreview=$file->LastProgress('Review');
	 	$lastprogress=$file->LastProgress();
	 	$this->lastreview=$lastreview;
	 		
	 	if(($this->lastreview!=null AND $this->lastreview->Result()==0) OR $lastprogress InstanceOf ReviewProgressClasseconfirm){
	 		return true;
	 	}else{
			return false;
	 	}
	}

}