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
	 * ID of ReviewFile
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $FileID;
	function FileID()
	{
		return $this->FileID;
	}
	function SetFileID($w)
	{
		$this->FileID=$w;
	}
	
	/**
	 * کد اظهارکننده
	 * @Column(type="string", length="30")
	 * @var string
	 */
	protected $DeclarantCoding;
	function DeclarantCoding()
	{
		return $this->DeclarantCoding;
	}
	function SetDeclarantCoding($w)
	{
		$this->DeclarantCoding=$w;
	}

	/**
	 * کد صاحب کالا
	 * @Column(type="string", length="30")
	 * @var string
	 */
	protected $OwnerCoding;
	function OwnerCoding()
	{
		return $this->OwnerCoding;
	}
	function SetOwnerCoding($w)
	{
		$this->OwnerCoding=$w;
	}
	
	/**
	 * همه اطلاعات آسی کودا
	 * @Column(type="text")
	 * @var string
	 */
	protected $Whole;
	function Whole()
	{
		$w=$this->Whole;
		return json_decode($w);
	}
	function SetWhole($w)
	{
		$w=json_encode($w);
		$this->Whole=$w;
	}
	
	/**
	 * 
	 * @param ReviewFile $File
	 * @param unknown_type $AsyArray
	 */
	function __construct($File,$AsyArray){
		$this->SetWhole($AsyArray);
		$this->SetFileID($File->ID());
		$this->SetDeclarantCoding("");
		$this->SetOwnerCoding("");
	}
	
	static function GetAsyByFile($File){
		$ans=ORM::Query("ConnectionAsy")->GetAsyByFile($File);
		if($ans){
			return $ans;
		}else{
			$c=new ConnectionBakedata();
			//$c->GetAsycuda($Cotag);
			//$r=new ConnectionAsy(); 
		}
	}
}

use \Doctrine\ORM\EntityRepository;
class ConnectionAsyRepository extends EntityRepository
{
	/**
	 * 
	 * @param ReviewFile $File
	 * @return unknown
	 */
	public function GetAsyByFile($File)
	{
		
		$fileID=$File->ID();
		$r=j::DQL("SELECT A FROM ConnectionAsy as A WHERE A.FileID=?",$fileID);
		return $r;
	}
	
}