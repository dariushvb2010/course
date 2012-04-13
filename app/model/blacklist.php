<?php

use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="BlacklistRepository")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class Blacklist
{
	/**
	 * @GeneratedValue @Id @Column(type="integer")
	 * @var integer
	 */
	protected $ID;
	function ID()
	{
		return $this->ID; 
	}
	
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $Coding;
	function Coding()
	{
		return $this->Coding;
	}

	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $State;
	function State()
	{
		return $this->State;
	}
	function SetState($value)
	{
		$this->State=$value;
	}
	
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $CreateTimestamp;
	function CreateTime()
	{
		$jc=new CalendarPlugin();
		return $jc->JalaliFromTimestamp($this->CreateTimestamp);
	}
	function __construct($Coding)
	{
		$this->CreateTimestamp=time();
		$this->Coding=$Coding;
		$this->SetState(0);
	}
}


use \Doctrine\ORM\EntityRepository;
class BlacklistRepository extends EntityRepository
{
	function Add($File){
		if(!$File)
			return 'اظهارنامه قید نشده است';
		//TODO:EzharnameObject.get coding
		//$Coding=??
		$r=new Blacklist($Coding);
	}
}