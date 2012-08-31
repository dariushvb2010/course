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
	protected $CreateTimestamp;
	
	function CreateTime()
	{
		$jc=new CalendarPlugin();
		return $jc->JalaliFromTimestamp($this->CreateTimestamp)." ".date("H:i:s",$this->CreateTimestamp);
	}
	
	/**
	 * ID of ReviewFile
	 * @Column(type="integer", unique=true)
	 * @var integer
	 */
	protected $FileID;
	function FileID(){ return $this->FileID; }
	function SetFileID($w){ $this->FileID=$w; }
	/**
	 * تاریخ ثبت کوتاژ cotag register date
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $RegTimestamp;
	function RegTimestamp(){ return $this->RegTimestamp; }
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
	function OwnerCoding(){ return $this->OwnerCoding; }
	function SetOwnerCoding($w){ $this->OwnerCoding=$w; }
	/**
	 * نام صاحب کالا
	 * @Column(type="string")
	 * @var string
	 */
	protected $OwnerName;
	function OwnerName(){ return $this->OwnerName; }
	function SetOwnerName($val){ $this->OwnerName=$val; }
	/**
	 * ارزیاب
	 * @Column(type="string", length="150")
	 * @var string
	 */
	protected $Arzyab;
	function Arzyab(){ return $this->Arzyab; }
	/**
	 * کارشناس سالن
	 * @Column(type="string", length="150")
	 * @var string
	 */
	protected $Karshenas_salon;
	function Karshenas_salon(){ return $this->Karshenas_salon; }
	/**
	 * کارشناس ارزش
	 * @Column(type="string", length="150")
	 * @var string
	 */
	protected $Karshenas_arzesh;
	function Karshenas_arzesh(){ return $this->Karshenas_arzesh; }
	/**
	 * مسیر اظهارنامه
	 * 1:green, 2:yellow, 3:red
	 * @example 1,2,3
	 * @Column(type="smallint", nullable=TRUE)
	 * @var integer
	 */
	protected $Masir;
	function Masir(){ return $this->Masir; }
	function setMasir($val){ $this->Masir = $val; }
	/**
	 * @Column (type="string", length="50")
	 * @var string
	 */
	protected $ArzPrice;
	function ArzPrice(){ return $this->ArzPrice; }
	/**
	 * @Column (type="string", length="50")
	 * @var string
	 */
	protected $RialPrice;
	function RialPrice(){ return $this->RialPrice; }
	/**
	 * @Column (type="string", length="50")
	 * @var string
	 */
	protected $TotalTexes;
	function TotalTaxes(){ return $this->TotalTexes; }
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
	function UpdateFieldsFromWhole()
	{
		
	}
	/**
	 * 
	 * @param ReviewFile $File
	 * @param unknown_type $AsyArray
	 */
	function __construct($File,$AsyArray){
		$this->CreateTimestamp=time();
		$this->SetWhole($AsyArray);
		$this->SetFileID($File->ID());
		$this->SetDeclarantCoding("");
		$this->SetOwnerCoding("");
		//$this->SetMasir('');
		$this->TotalTexes="";
	}
	
	static function GetAsyByFile($File){
		$ans=ORM::Query("ConnectionAsy")->GetAsyByFile($File);
		if($ans){
			return $ans;
		}else{
			return null;
		}
	}
	
	static function DeleteAsyByFile($File){
		$ans=self::GetAsyByFile($File);
		if($ans){
			ORM::Delete($ans);
			ORM::Flush();
		}else{
			return null;
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
	static function UpdateAll(){
		$r=j::DQL("SELECT A FROM ConnectionAsy as A LIMIT 1,20");
		/**foreach ($r as $v) {
		 $d=$v->Masir();
		if(!isset($d)){
		$t=json_decode($v->Whole());
		$v->setMasir($t->masir);
		}
		}**/
	}
	
}