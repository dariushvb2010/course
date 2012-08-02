<?php
/**
 * class for caching AsyCuda information of a cotag
 * @author dariush
 * @Entity
 * @Entity(repositoryClass="ConnectionAsyRepository")
 */
class ConnectionAsy extends JModel
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
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $Cotag;
	function Cotag()
	{
		return $this->Cotag;
	}
	/**
	 * شماره مجوز بارگیری
	 * Bargiri Serail
	 * @Column(type="string", length="100")
	 * @var string
	 */
	protected $BarSerial;
	function BarSerial()
	{
		return $this->BarSerial;
	}
	/**
	 * سال ثبت کوتاژ
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $RegYear;
	function RegYear()
	{
		return $this->RegYear;
	}
	/**
	 * کد اظهارکننده
	 * @Column(type="string", length="30")
	 * @var string
	 */
	protected $DeclarantCode;
	function DeclarantCode()
	{
		return $this->DeclarantCode;
	}
	/**
	 * همه اطلاعات آسی کودا
	 * @Column(type="text")
	 * @var string
	 */
	protected $Whole;
	function Whole()
	{
		return $this->Whole;
	}
}

use \Doctrine\ORM\EntityRepository;
class ConnectionAsyRepository extends EntityRepository
{
	
}