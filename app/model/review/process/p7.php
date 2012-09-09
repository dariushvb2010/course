<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessP7Repository")
 * */
class ReviewProcessP7 extends ReviewProgress
{
	
	function __construct(ReviewFile $File=null,MyUser $User=null)
	{
		parent::__construct($File,$User);
	}	
	function  Summary()
	{
		$str="صاحب کالا در لیست ماده ۷  ثبت شد.";
		return $str;
	}
	function Title()
	{
		return "ماده ۷ ";
	}
	function Manner()
	{
		return "P7";
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessP7Repository extends EntityRepository
{
	/**
	 * 
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File)
	{
		$R=new ReviewProcessP7($File);
		$err=$R->Check();
		if(is_string($err))
			return $res;
		
		$R->Apply();
		ORM::Persist($R);
		
		return $res;
	}
}