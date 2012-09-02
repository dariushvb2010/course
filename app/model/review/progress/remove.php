<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProgressRemoveRepository")
 * */
class ReviewProgressRemove extends ReviewProgress
{
	/**
	 * @OneToOne(targetEntity="ReviewProgress")
	 * @JoinColumn(name="SlainID", referencedColumnName="ID")
	 */
	protected $Slain;
	function Slain()
	{
		return $this->Slain;
	}



	function __construct(ReviewProgress $Slain=null, MyUser $User=null,$Comment='')
	{
		parent::__construct($Slain->File(),$User);
		$this->Comment=$Comment;
		$this->Slain=$Slain;
		$this->SetDead();
	}

	function  Summary()
	{
		return "فرآیند قبلی ("."<strong> '".$this->Slain->Title()."' </strong>)"."حذف شد.";
	}
	function Title()
	{
		return "حذف یک فرآیند";
	}
	function Event(){
		return 'Remove';
	}

}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressRemoveRepository extends EntityRepository
{
	/**
	 * 
	 * @param ReviewProgress $Slain
	 * @param string $Comment
	 * @return string(error)|boolean
	 */
	public function AddToFile(ReviewProgress $Slain,$Comment)
	{
		if(!$Slain){
			return "فرایندی نداده اید.";
		}
		if(!$Slain->File()){
			return v::Ecnv();
		}
		if($Slain->PrevState()==0)
		{
			return "امکان حذف کردن این فرآیند وجود ندارد. ";
		}
		if(strlen($Comment)<10){
			return "توضیحات کافی نیست.";
		}
		$User=MyUser::CurrentUser();
		$R=new ReviewProgressRemove($Slain,$User,$Comment);
		ORM::Persist($R);
		return true;
	}
}
