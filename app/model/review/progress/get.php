<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressGetRepository")
 * */
class ReviewProgressGet extends ReviewProgress
{
	/**
	*
	* @OneToOne(targetEntity="ReviewProgressGive", inversedBy="ProgressGet")
	* @JoinColumn(name="ProgressGiveID",referencedColumnName="ID", nullable=false)
	* @var ReviewProgressGive
	*/
	protected $ProgressGive;
	function ProgressGive(){ return $this->ProgressGive; }
	function SetProgressGive(ReviewProgressGive $ProgressGive)
	{
		$this->ProgressGive=$ProgressGive;
		
	}
	function AssignProgressGive(ReviewProgressGive $ProgressGive)
	{
		$this->ProgressGive=$ProgressGive;
		$ProgressGive->SetProgressGet($this);
	}
	function __construct(ReviewFile $File=null, $IfPersist=true)
	{
		$User=MyUser::CurrentUser();
		parent::__construct($File, $User, $IfPersist);
		$ProgressGive=$File->LLP("Give");
		if($ProgressGive)
		$IfPersist ? $this->AssignProgressGive($ProgressGive) : $this->SetProgressGive($ProgressGive);
		
	}
	function  Summary()
	{
		
	}
	function Title()
	{
		return "تحویل به ";
	}
	function Event()
	{
		return "";
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressGetRepository extends EntityRepository
{
	/**
	* @param ReviewFile|string|integer $File
	* @return string|Ambigous <string, number>
	*/
	public function AddToFile($File=null, $IfPersist=true)
	{
		$File=ReviewFile::GetRecentFile($File);
		if(!($File instanceof ReviewFile))
		{
			return "اظهارنامه یافت نشد.";
		}
		$P=new ReviewProgressGet($File, $IfPersist);
		$ch=$IfPersist ? $P->Apply() : $P->Check();
		if(is_string($ch))
			return $ch;
		if($IfPersist)
		{
			ORM::Persist($P);
		}
		return $P;
	}
}