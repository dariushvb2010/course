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
	 * SELECT * FROM `app_ConnectionAsy` as A left outer join app_ReviewFile F on A.FileID=F.ID where F.Cotag is null 
	 * @OneToOne(targetEntity="ReviewFile", inversedBy="Stock")
	 * @JoinColumn(name="FileID", referencedColumnName="ID")
	 * @var ReviewFile
	 */
	protected $File;
	function File(){ return $this->File; }
	function SetFile($File){ $this->File=$File; }
	function AssignFile(ReviewFile $File)
	{
		$this->File=$File;
		$File->SetAsy($this);
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
	 * @Column(type="smallint")
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
	 * @Column (type="string", length="8")
	 * @var string
	 */
	protected $ArzType;
	function ArzType(){ return $this->ArzType; }
	/**
	 * @Column (type="string", length="50")
	 * @var string
	 */
	protected $ArzNerkh;
	function ArzNerkh(){ return $this->ArzNerkh; }
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
		$AsyArray=$this->MaintainJsonInput($AsyArray);

		
		$this->SetWhole($AsyArray);
		$this->AssignFile($File);
		$this->CreateTimestamp=time();
		
		$this->UpdateFields();
	}
	
	/**
	 * trims all array cells 
	 * and remove multiple spaces
	 * @param unknown_type $inp
	 */
	private function MaintainJsonInput($inp){
		$AsyArray=json_decode(json_encode($inp),true);
		
		array_walk_recursive($AsyArray, array($this,'trim_value'));
		$AsyArray=json_decode(json_encode($AsyArray));
		return $AsyArray;
	}
	function trim_value(&$value)
	{
		$value = trim($value);
		$value = preg_replace('/\s+/', ' ',$value);
	}
	
	private function FixNulls($value){
		return ($value==null?'':$value);
	}
	
	function UpdateFields(){
		$AsyArray=$this->Whole();
		
		
		if($this->RegTimestamp) return;
		
		$c=new CalendarPlugin();
		$dat=$c->ExtractElements($AsyArray->kutajDateS);
		$this->RegTimestamp=$c->Jalali2Timestamp($dat[0], $dat[1], $dat[2]);;
		
		
		$this->Karshenas_salon=$AsyArray->asyKarshenas;
		
		$arzar=explode('-',$AsyArray->asyArzyab);
		if (count($arzar)<2)$arzar=array('','');
		$this->Arzyab=$arzar[0];
		$this->Karshenas_arzesh=$arzar[1];
		
		$this->OwnerName=$AsyArray->owner->nameOfPerson;
		$this->SetOwnerCoding($AsyArray->owner->personCode);
		
		$this->SetDeclarantCoding($AsyArray->claimer->personCode);
		
		$this->ArzType=$AsyArray->arzType;
		$this->ArzNerkh=$AsyArray->arzNerkh;
		$this->ArzPrice=$AsyArray->totalGheimatBehArz;
		$this->RialPrice=$AsyArray->totalGheimatBehRial;
		
		$this->setMasir($this->getMasir($AsyArray->masir));
		
		$this->TotalTexes=$AsyArray->totalTaxes;		
	}
	
	private function startsWith($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}
	private function getMasir($inp) {
		if($this->startsWith($inp, "YEL"))
			$str='yellow';
		else if($this->startsWith($inp, "RED"))
			$str='red';
		else
			$str='green';
		$prefix='';
		return $prefix.$str;
	}
	
	static function GetAsyByFile($File){
		$ans=ORM::Query("ConnectionAsy")->GetAsyByFile($File);
		if($ans){
			$ans->UpdateFields();
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
		$r=j::ODQL("SELECT A FROM ConnectionAsy as A WHERE A.File=?",$File);
		return $r[0];
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