<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="ProcessAssign")
 * @entity(repositoryClass="ReviewProcessAssignRepository")
 * */
class ReviewProcessAssign extends ReviewProgress
{
	
	/**
	* @ManyToOne(targetEntity="MyUser");
	* @JoinColumn(name="ExpertID",referencedColumnName="ID")
	* @var MyUser
	*/
	protected  $ProcessReviewer;
	function ProcessReviewer(){ return $this->ProcessReviewer; }
	
	function __construct(ReviewFile $File=null, MyUser $ProcessReviewer=null, MyUser $User=null)
	{
		parent::__construct($File,$User);
		if($ProcessReviewer)
			$this->ProcessReviewer=$ProcessReviewer;
	}
	
	function  Summary()
	{
		if($this->ProcessReviewer())
			return "پرونده به کارشناس بازبینی ".v::b($this->ProcessReviewer()->getFullName())."  تحویل داده شد.";
		else 
			return "خطا در گزارش گیری";
	}
	function Title()
	{
		$fsmp = FsmGraph::GetProgressByName($this->Manner());
		return $fsmp->Label;
	}
	function Manner()
	{
		return "ProcessAssign";
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessAssignRepository extends EntityRepository
{
	/**
	 * 
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File, $ProcessReviewerID, $Comment='')
	{
		$reviewer = ORM::Find('MyUser', $ProcessReviewerID);
		if(!$reviewer)
			return v::Ernf();
		if($reviewer->getState()!=MyUser::State_work)
			return v::Erd();
		$R=new ReviewProcessAssign($File,$reviewer);
		$R->setComment($Comment);
		$er = $R->Check();
		if(is_string($er))
			return $er;
		
		$R->Apply();
		ORM::Persist($R);
		return $R;
	}
}