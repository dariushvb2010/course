<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
*
* @Entity 
* @Entity(repositoryClass="FsmStateRepository")
* DiscriminatorColumn(name="discriminator", type="string")
* DiscriminatorMap({"dbvalue"="class"})
**/
class FsmState extends JModel
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
	protected $Num;
	public function Num()
	{
		return $this->Num;
	}
	/**
	* @Column(type="string")
	* @var integer
	*/
	protected $Str;
	public function Str()
	{
		return $this->Str;
	}
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Summary;
	public function Summary()
	{
		return $this->Summary;
	}
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Place;
	public function Place()
	{
		return $this->Place;
	}
	
	function __construct($Num=123, $Str='Str', $Summary='tozihat', $Place='بیرون')
	{
		 $this->Num=$Num;
		 $this->Str=$Str;
		 $this->Summary=$Summary;
		 $this->Place=$Place;
	}
	public static $Status=array(
	"Cotag/Started"=>2,
	"Cotag/SendedToArchive"=>3,
	"Archive/Raw"=>4,
	"Review/Raw"=>5,
	"Review|Manager/Reviewed/Nok"=>6,
	"Review/Reviewed/Ok"=>7,
	"Manager/Confirmed/Ok"=>9,
	"Manager|Review/Confirmed/Nok"=>10,
	"Archive/Reviewed"=>11
	);
}

use \Doctrine\ORM\EntityRepository;
class FsmStateRepository extends EntityRepository
{
	public function Add($Num, $Str, $Summary, $Place)
	{
		$s=ORM::Find('FileState', 'Num',$Num);
		if($s)
		{
			$r="این وضعیت قبلا ثبت شده است.";
			return $r;
		}
		$r=new FileState($Num, $Str, $Summary, $Place);
		ORM::Write($r);
	}
	public function GetAllStates()
	{
		$r=j::ODQL("SELECT S FROM FileState AS S");
		return $r;
	}
}
