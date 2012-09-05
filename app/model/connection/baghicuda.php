<?php
/**
 * class for caching Bagher Exit door information of a cotag
 * @author dariush
 * @Entity
 * @Entity(repositoryClass="ConnectionBaghicudaRepository")
 */
class ConnectionBaghicuda extends JModel
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
	}
	
	static function GetAsyByFile($File){
		$ans=ORM::Query("ConnectionAsy")->GetAsyByFile($File);
		if($ans){
			return $ans;
		}else{
			return null; 
		}
	}
}

use \Doctrine\ORM\EntityRepository;
class ConnectionBaghicudaRepository extends EntityRepository
{
	/**
	 * 
	 * @param ReviewFile $File
	 * @return unknown
	 */
	public function GetAsyByFile($File)
	{
		
		$fileID=$File->ID();
		$r=j::DQL("SELECT A FROM ConnectionBaghicuda as A WHERE A.FileID=?",$fileID);
		return $r;
	}
	
}