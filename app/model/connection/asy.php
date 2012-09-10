<?php

/**
 * class for caching AsyCuda information of a cotag
 * @author dariush
 * @Entity
 * @Entity(repositoryClass="ConnectionAsyRepository")
 */
class ConnectionAsy extends JModel {

    /**
     * @GeneratedValue @Id @Column(type="integer")
     * @var integer
     */
    protected $ID;

    function ID() {
        return $this->ID;
    }

    /**
     * SELECT * FROM `app_ConnectionAsy` as A left outer join app_ReviewFile F on A.FileID=F.ID where F.Cotag is null 
     * @OneToOne(targetEntity="ReviewFile", inversedBy="Stock")
     * @JoinColumn(name="FileID", referencedColumnName="ID")
     * @var ReviewFile
     */
    protected $File;

    function File() {
        return $this->File;
    }

    function SetFile($File) {
        $this->File = $File;
    }

    function AssignFile(ReviewFile $File) {
        $this->File = $File;
        $File->SetAsy($this);
    }

    /**
     * @Column(type="integer")
     * @var integer
     */
    protected $CreateTimestamp;

    function CreateTime() {
        $jc = new CalendarPlugin();
        return $jc->JalaliFromTimestamp($this->CreateTimestamp) . " " . date("H:i:s", $this->CreateTimestamp);
    }

    /**
     * تاریخ ثبت کوتاژ cotag register date
     * @Column(type="integer")
     * @var integer
     */
    protected $RegTimestamp;

    function RegTimestamp() {
        return $this->RegTimestamp;
    }

    function RegTime() {
        $jc = new CalendarPlugin();
        return $jc->JalaliFromTimestamp($this->RegTimestamp());
    }

    /**
     * کد اظهارکننده
     * @Column(type="string", length="30")
     * @var string
     */
    protected $DeclarantCoding;

    function DeclarantCoding() {
        return $this->DeclarantCoding;
    }

    function SetDeclarantCoding($w) {
        $this->DeclarantCoding = $w;
    }
	/**
	 * @Column(type="string")
	 * @var string
	 */
    protected $DeclarantName;
    public function DeclarantName() {
        return $this->DeclarantName;
    }

    public function setDeclarantName($DeclarantName) {
        $this->DeclarantName = $DeclarantName;
    }

        /**
     * کد صاحب کالا
     * @Column(type="string", length="30")
     * @var string
     */
    protected $OwnerCoding;

    function OwnerCoding() {
        return $this->OwnerCoding;
    }

    function SetOwnerCoding($w) {
        $this->OwnerCoding = $w;
    }

    /**
     * نام صاحب کالا
     * @Column(type="string")
     * @var string
     */
    protected $OwnerName;

    function OwnerName() {
        return $this->OwnerName;
    }

    function SetOwnerName($val) {
        $this->OwnerName = $val;
    }
    
    /**
     * آدرس صاحب کالا
     * @Column(type="string", nullable=true)
     * @var string
     */
    protected $OwnerAddress;
    function OwnerAddress() { return $this->OwnerAddress; }
    function SetOwnerAddress($val) { $this->OwnerAddress = $val; }
    /**
     * ارزیاب
     * @Column(type="string", length="150")
     * @var string
     */
    protected $Arzyab;

    function Arzyab() {
        return $this->Arzyab;
    }

    /**
     * کارشناس سالن
     * @Column(type="string", length="150")
     * @var string
     */
    protected $Karshenas_salon;

    function Karshenas_salon() {
        return $this->Karshenas_salon;
    }

    /**
     * کارشناس ارزش
     * @Column(type="string", length="150")
     * @var string
     */
    protected $Karshenas_arzesh;

    function Karshenas_arzesh() {
        return $this->Karshenas_arzesh;
    }

    /**
     * مسیر اظهارنامه
     * 1:green, 2:yellow, 3:red
     * @example 1,2,3
     * @Column(type="smallint")
     * @var integer
     */
    protected $Masir;

    function Masir() {
        switch ($this->Masir) {
            case 0:
                return null;
                break;
            case 1:
                return "سبز";
                break;
            case 2:
                return "زرد";
                break;
            case 3:
                return "قرمز";
                break;
            default:
                break;
        }
    }

    function setMasir($val) {
        $this->Masir = $val;
    }

    /**
     * @Column (type="string", length="50")
     * @var string
     */
    protected $ArzPrice;

    function ArzPrice() {
        return $this->ArzPrice;
    }

    /**
     * @Column (type="string", length="8")
     * @var string
     */
    protected $ArzType;

    function ArzType() {
        return $this->ArzType;
    }

    /**
     * @Column (type="string", length="50")
     * @var string
     */
    protected $ArzNerkh;

    function ArzNerkh() {
        return $this->ArzNerkh;
    }

    /**
     * @Column (type="string", length="50")
     * @var string
     */
    protected $RialPrice;

    function RialPrice() {
        return $this->RialPrice;
    }

    /**
     * @Column (type="string", length="50")
     * @var string
     */
    protected $TotalTexes;

    function TotalTaxes() {
        return $this->TotalTexes;
    }

    /**
     * همه اطلاعات آسی کودا
     * @Column(type="text")
     * @var string
     */
    protected $Whole;

    function Whole() {
        $w = $this->Whole;
        return json_decode($w);
    }

    function SetWhole($w) {
        $w = json_encode($w);
        $this->Whole = $w;
    }

    function UpdateFieldsFromWhole() {
        
    }

    /**
     * 
     * @param ReviewFile $File
     * @param unknown_type $AsyArray
     */
    function __construct($File, $AsyArray) {
        $AsyArray = $this->MaintainJsonInput($AsyArray);


        $this->SetWhole($AsyArray);
        $this->AssignFile($File);
        $this->CreateTimestamp = time();

        $this->UpdateFields();
    }

    /**
     * trims all array cells 
     * and remove multiple spaces
     * @param unknown_type $inp
     */
    private function MaintainJsonInput($inp) {
        $AsyArray = json_decode(json_encode($inp), true);

        array_walk_recursive($AsyArray, array($this, 'trim_value'));
        $AsyArray = json_decode(json_encode($AsyArray));
        return $AsyArray;
    }

    function trim_value(&$value) {
        $value = trim($value);
        $value = preg_replace('/\s+/', ' ', $value);
    }

    private function FixNulls($value) {
        return ($value == null ? '' : $value);
    }

    function UpdateFields() {
        $AsyArray = $this->Whole();


        if ($this->RegTimestamp && $this->Masir)
            return;

        $c = new CalendarPlugin();
        $dat = $c->ExtractElements($AsyArray->kutajDateS);
        $this->RegTimestamp = $c->Jalali2Timestamp($dat[0], $dat[1], $dat[2]);
        ;


        $this->Karshenas_salon = $AsyArray->asyKarshenas;

        $arzar = explode('-', $AsyArray->asyArzyab);
        if (count($arzar) < 2)
            $arzar = array('', '');
        $this->Arzyab = $arzar[0];
        $this->Karshenas_arzesh = $arzar[1];
        $this->OwnerName = $AsyArray->owner->nameOfPerson;
        $this->SetOwnerCoding($AsyArray->owner->personCode);
		$this->SetOwnerAddress( $AsyArray->owner->adress );
        $this->SetDeclarantCoding($AsyArray->claimer->personCode);

        $this->ArzType = $AsyArray->arzType;
        $this->ArzNerkh = $AsyArray->arzNerkh;
        $this->ArzPrice = $AsyArray->totalGheimatBehArz;
        $this->RialPrice = $AsyArray->totalGheimatBehRial;

        $this->setMasir($this->getMasir($AsyArray->masir));
        $this->setDeclarantName($AsyArray->claimer->nameOfPerson);
        $this->TotalTexes = $AsyArray->totalTaxes;
        
        
    }

    private function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    private function getMasir($inp) {
        if ($this->startsWith($inp, "YEL"))
            return 2;
        else if ($this->startsWith($inp, "RED"))
            return 3;
        else
            return 1;
    }

    static function GetAsyByFile($File) {
        $ans = ORM::Query("ConnectionAsy")->GetAsyByFile($File);
        if ($ans) {
            $ans->UpdateFields();
            return $ans;
        }
        else {
            return null;
        }
    }

    static function DeleteAsyByFile($File) {
        $ans = self::GetAsyByFile($File);
        if ($ans) {
            ORM::Delete($ans);
            ORM::Flush();
        }
        else {
            return null;
        }
    }

    function Cotag() {
        return $this->File()->Cotag();
    }

    

    function getAll() {
        $this->UpdateFields();
        $arr2[] = array("Value" => $this->Cotag());
        $arr2[] = array("Value" => $this->RegTime());
        $arr2[] = array("Value" => $this->Masir());
        $arr2[] = array("Value" => $this->OwnerName());
        $arr2[] = array("Value" => $this->OwnerCoding());
        $arr2[] = array('Value' => $this->OwnerAddress());
        $arr2[] = array("Value" => $this->DeclarantName());
        $arr2[] = array("Value" => $this->DeclarantCoding());
        $arr2[] = array("Value" => $this->Karshenas_salon());
        $arr2[] = array("Value" => $this->Arzyab());
        $arr2[] = array("Value" => $this->Karshenas_arzesh());
        $arr2[] = array("Value" => $this->ArzType());
        $arr2[] = array("Value" => $this->ArzNerkh());
        $arr2[] = array("Value" => $this->RialPrice());
        $arr2[] = array("Value" => $this->TotalTaxes());
        return $arr2;
    }
    function getPersianTitles() {
        $titles = array(p::Cotag, p::RegTime, p::Masir, p::OwnerName, p::OwnerCoding,
            p::OwnerAddress, p::DeclarantName, p::DeclarantCoding, p::Karshenas_salon, p::Arzyab,
            p::Karshenas_arzesh, p::ArzType, p::ArzNerkh, p::RialPrice, p::TotalTaxes);
        return $titles;
    }

}

use \Doctrine\ORM\EntityRepository;

class ConnectionAsyRepository extends EntityRepository {

    /**
     * 
     * @param ReviewFile $File
     * @return unknown
     */
    public function GetAsyByFile($File) {
        $r = j::ODQL("SELECT A FROM ConnectionAsy as A WHERE A.File=?", $File);
        return $r[0];
    }

}
