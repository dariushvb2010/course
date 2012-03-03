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
	function __construct(ReviewFile $File=null)
	{
		parent::__construct($File);
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
	
}