<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressCorrectionRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressCorrection extends ReviewProgress
{
	/**
	* @Column(type="string")
	* @var string
	*/
	protected  $OldCotag;
	function OldCotag()
	{
		return $this->OldCotag;
	}
	function setOldCotag($value)
	{
		$this->OldCotag=$value;
	}
	
	/**
	* @Column(type="string")
	* @var string
	*/
	protected  $NewCotag;
	function NewCotag()
	{
		return $this->NewCotag;
	}
	function setNewCotag($value)
	{
		$this->NewCotag=$value;
	}
	
	function __construct(ReviewFile $File=null,MyUser $Corrector=null)
	{
		parent::__construct($File,$Corrector);
	}
	
	function  Summary()
	{
		if($this->User())
			return "شماره کوتاژ اظهارنامه از ".$this->OldCotag()." به ".$this->NewCotag()."اصلاح گردید.".'<br>'.$this->Comment();
		else 
			return "خطا در گزارش گیری";
	}
	
	function Title()
	{
		return "اصلاح";
	}
	
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressCorrectionRepository extends EntityRepository
{
	/**
	 * 
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile($OldCotag,$NewCotag,$Comment=null)
	{
		
		$CurrentUser=MyUser::CurrentUser();
		
		$file1 = ORM::query(new ReviewFile)->GetRecentFile($OldCotag);
		if($OldCotag==null OR $file1==null)
		{
			$Error="چنین کوتاژی موجود نیست.";
			return $Error;
		}
		
		$file2 = ORM::query(new ReviewFile)->GetRecentFile($NewCotag);
		if($NewCotag==null OR $file2!=null)
		{
			$Error="کوتاژ جدید به اظهارنامه ی  دیگری متعلق است.";
			return $Error;
		}
		
		if (strlen($Comment)<15)
		{
			$Error="حداقل توضیحی به طول 10 حرف نیاز دارد.";
			return $Error;
		}
		
		$R=new ReviewProgressCorrection($file1,$CurrentUser);
		$R->kill();
		$R->setComment($Comment);
		$R->setOldCotag($OldCotag);
		$R->setNewCotag($NewCotag);
		
		$file1->setCotag($NewCotag);
		ORM::Persist($file1);
		
		ORM::Persist($R);
		
		
		return $R;
		
	}

}