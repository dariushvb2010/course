<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @Entity(repositoryClass="ConfigMainRepository")
 * */
class ConfigMain extends Config
{
	
	/**
	 * The Name of the constant variable used at review unit
	 * @Column(type="string",unique=true)
	 * @var string
	 */
	protected $Name;
	public function Name()
	{
		return $this->Name;
	}
	function SetName($Name)
	{
		$this->Name=$Name;
	}
	/**
	 * Value of the constant variable used at review unit
	* @Column(type="string")
	* @var string
	*/
	protected $Value;
	public function Value()
	{
		return $this->Value;
	}
	function SetValue($Value)
	{
		$this->Value=$Value;
	}
	/**
	* PersianName
	* @Column(type="string", nullable=true)
	* @var string
	*/
	protected $PersianName;
	public function PersianName()
	{
		return $this->PersianName;
	}
	function SetPersianName($Value)
	{
		$this->PersianName=$Value;
	}
	
	function __construct($Name=null,$Value='', $DeleteAccess=true, $Comment=null, $PersianName=null)
	{
		parent::__construct($DeleteAccess,$Comment);
		
		if($Name)
			$this->Name=$Name;

		$this->Value=$Value;
		$this->PersianName=$PersianName;
	}
	public static function Add($Name,$Value, $DeleteAccess, $Comment='',$PersianName=null)
	{
		if ($Name==null)
		{
			$Error="رکورد نمی تواند بدون نام باشد.";
			return $Error;
		}
		elseif ($Value==null)
		{
			$Error="رکورد نمی تواند بدون مقدار باشد.";
			return $Error;
		}
		else
		{
			$f=ORM::Find1("ConfigMain", "Name",$Name);
			if($f)
				return "عنوان تکراری است.";
			$c=new ConfigMain($Name, $Value, $DeleteAccess, $Comment, $PersianName);
			ORM::Write($c);
			return $c;
		}
	
	}
	public static function Init()
	{
		foreach (ConfigData::$MAIN as $Name=>$Data)
		{
			$r=self::Add($Name, $Data['Value'], $Data['DeleteAccess'], $Data['Comment']);
			if(is_string($r))
				$res[]=$r;
			else 
				$res[]="added! ";
		}
		return $res;
	}
	
}


use \Doctrine\ORM\EntityRepository;
class ConfigMainRepository extends EntityRepository
{
	public function GetAll()
	{
		$r=j::ODQL("SELECT M FROM ConfigMain M");
		return $r;	
	}
	public function GetValue($Name)
	{
		$r=j::ODQL("SELECT M FROM ConfigMain M WHERE M.Name=?",$Name);
		if($r)
		return $r[0]->Value();
	}
	public function GetObject($Name)
	{
		$r=ORM::Find("ConfigMain", "Name",$Name);
		if($r)
			return $r[0];
	}
	public function Delete(ConfigMain $M)
	{
		if($M->DeleteAccess())
		{
			j::DQL("DELETE FROM ConfigMain WHERE M.ID=? ",$M->ID());
			return true;
		}
		else 
		{
			return "امکان حذف کردن وجود ندارد.";
		}
	}
}