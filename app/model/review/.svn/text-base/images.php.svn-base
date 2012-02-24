<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity Table(name="ReviewImages")
 * @Entity(repositoryClass="ReviewImagesRepository")
 * */
class ReviewImages
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
	 * @ManyToOne(targetEntity="ReviewProgress")
 	 * @JoinColumn(name="PID", referencedColumnName="ID")
	 */
    public $PID;
	function PID()
	{
		return $this->PID;
	}
	
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $Name;
	public function Name()
	{
		return $this->Name;
	}
	function __construct($PID=null,$Name=null)
	{
		$this->PID=$PID;
		$this->Name=$Name;
	}
	
	/**
	*
	* Enter description here ...
	* @param string
	* @return string on error object on sucsess
	*/
	public static function Add($PID,$Name)
	{
		if (!$PID || !$Name)
		{
			return false;
		}
		else
		{
			$I=new ReviewImages($PID, $Name);
			ORM::Persist($I);
			return true;
		}
	
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewImagesRepository extends EntityRepository
{
	/**
	 *
	 * return an array of all of pics of a cotag used for slider
	 * @param unknown_type $Cotag
	 */
	function AllDossierImages($Cotag)
	{
		$File=ORM::Query(new ReviewFile())->GetRecentFile($Cotag);
		if($File)
		$r=j::ODQL("SELECT I FROM ReviewImages AS I JOIN I.PID AS P WHERE P.File=? AND P.Dead=0 ORDER BY P.CreateTimestamp",$File);
		return $r;
		
	}
}