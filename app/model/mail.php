<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * 
 * an abstract class for using in progresses Send, Receive and Give
 * @author dariush jafari
 * @Entity
 * @entity(repositoryClass="MailRepository")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="Type", type="string")
 * @DiscriminatorMap({"Base" = "Mail",
 * 	"Send"="MailSend",
 * 	"Receive"="MailReceive",
 * 	"Give"="MailGive"
 * 	})
 */
abstract class Mail
{
	/**
	 * @GeneratedValue @Id @Column(type="integer")
	 * @var integer
	 */
	protected $ID;
	function ID(){ return $this->ID; }
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Num;
	public function Num(){ return $this->Num; }
	protected function SetNum($Num){ $this->Num=$Num; }
	static function MailNumValidation($Num)
	{
		if($Num)
			return true;
		else
			return false;
	}
	/**
	* @Column(type="string", nullable="true")
	* @var string
	*/
	protected $Subject;
	public function Subject(){ return $this->Subject; }
	protected function SetSubject($Subject){ $this->Subject=$Subject; }
	/**
	 * state of editting the mail,
	 * mail has not been transfered yet, it just has been saved to be transfered in the future
	 * @var integer
	 */
	const STATE_EDITING=1;//--------------------1-------------
	protected function StateEditing()
	{
		if($this->CanIGoTo(self::STATE_EDITING))
		{
			$this->State=self::STATE_EDITING;
			$this->RetouchTimestamp=time();
		}
		else 
			throw new Exception();
	}
	/**
	 * state of editing the mail, some of files has been stransfered and some not.
	 * this case is very scarse
	 * @var integer
	 */
	const STATE_EDITING_FAULTY=2;//-------------2-------------
	protected function StateEditingFaulty()
	{
		if($this->CanIGoTo(self::STATE_EDITING_FAULTY))
		{
			$this->State=self::STATE_EDITING_FAULTY;
			j::Log("Strange", "mail went to STATE_EDITING_FAULTY, mailID=".$this->ID." at <br/>model/mail");
		}	
		else
			throw new Exception();
	}
	/**
	 * 
	 * mail has been transfered, and the man in the destination can open the mail
	 * @var integer
	 */
	const STATE_INWAY=4;//----------------------4---------------
	protected function StateInway()
	{
		$res=true;
		foreach ($this->Stock() as $s)
		$res &=$s->Act();
		if($res)
		{
			if($this->CanIGoTo(self::STATE_INWAY))
			{
				foreach ($this->Stock() as $s)
				{
					$s->SetError(null);
					$s->SetIfSaveGet(false);
					$s->SetAct(false);
				}
				$this->State=self::STATE_INWAY;
				$this->EventTimestamp=time();
				return true;
			}
			else
				throw new Exception();
		}
		else
			return false;
	}
	/**
	 * the man at the destination unit catched the mail, and can catch the files of the mail
	 * @var integer
	 */
	const STATE_GETTING=6;//---------------------6-----------------
	protected function StateGetting()
	{
		if($this->CanIGoTo(self::STATE_GETTING))
		{
			$this->State=self::STATE_GETTING;
		}
		else
			throw new Exception();
	}
	/**
	* the man at the destination unit catched the mail, but catched uncompletely,
	* e.t some of files has been catched and set their progress and some not
	* @var integer
	*/
	const STATE_GETTING_FAULTY=7;//---------------------7-----------------
	protected function StateGettingFaulty()
	{
		if($this->CanIGoTo(self::STATE_GETTING_FAULTY))
		{
			$this->State=self::STATE_GETTING_FAULTY;
		}
		else
			throw new Exception();
	}
	/**
	 * all of the operations have been done and mail has been closed
	 * @var integer
	 */
	const STATE_CLOSED=9;//---------------------9-------------------
	protected function StateClosed()
	{
		$res=true;
		
		foreach ($this->Stock() as $s)
		{
			$res &=$s->Act();
		}
		if($res and count($this->Stock()))
		{
			if($this->CanIGoTo(self::STATE_CLOSED))
			{
				foreach ($this->Stock() as $s)
				{
					$File=$s->File();
					$File->SetStock(null);
					$this->Stock->removeElement($s);
					$s->SetMail(null);
					ORM::Delete($s);
				}
				$this->State=self::STATE_CLOSED;
				if(!($this instanceof MailGive))
					$this->EventTimestamp=time();
				return true;
			}
			else
			throw new Exception();
		}
		else
			return false;
	}
	/**
	 * I have My own state, can I go to new State
	 * @param integer $S1
	 * @param integer $S2
	 * @return Boolean
	 */
	private function CanIGoTo($NewState)
	{
		if(!isset($this->State) OR !isset($NewState))
			return false;
		return $this->State<$NewState;
	}
	function CanEdit()
	{
		if($this->State > self::STATE_EDITING_FAULTY)
			return false;
		else
			return true;
	}
	function Type()
	{
		if($this instanceof MailGive)
			return "Give";
		elseif($this instanceof MailSend)
			return "Send";
		elseif($this instanceof MailReceive)
			return "Receive";
	}
	public static $PersianState=array(
		self::STATE_EDITING=>"در حال ویرایش",
		self::STATE_EDITING_FAULTY=>"ارسال شده ناقص، در حال ویرایش",
		self::STATE_INWAY=>"در راه",
		self::STATE_GETTING=>"در حال تحویل گیری",
		self::STATE_GETTING_FAULTY=>"تحویل گیری ناقص",
		self::STATE_CLOSED=>"مختومه"
	);
	function PersianState()
	{
		return self::$PersianState[$this->State];
	}
	/**
	* @Column(type="integer")
	* @var integer
	*/
	private $State;
	function State(){return $this->State;}
	//please dont Declare SetState function, we have alternative safe functions for that 
	/**
	 * Timestamp of closing the mail
	* @Column(type="integer", nullable=true)
	* @var integer
	*/
	protected $CloseTimestamp;
	function CloseTimestamp(){ return $this->CloseTimestamp;}
	function CloseTime()
	{
		$jc=new CalendarPlugin();
		return $jc->JalaliFullTime($this->CloseTimestamp);
	}
	/**
	* the last Retouch timestamp.
	* Retouch=action(give or get or send or receive) | save
	* if mail is closed the retouchtimestamp will hold the last action
	* @Column(type="integer") 
	* @var integer
	*/
	protected $RetouchTimestamp;
	function RetouchTimestamp(){return $this->RetouchTimestamp;}
	
	/**
	* @Column(type="string",nullable=true)
	* @var string
	*/
	protected $Description;
	public function Description() { return $this->Description; }
	protected function SetDescription( $D){ $this->Description=$D; }
	/**
	* @OneToMany(targetEntity="FileStock", mappedBy="Mail", cascade={"remove"})
	* @var arrayCollectionOfFileStock
	*/
	protected $Stock;
	function Stock(){ return $this->Stock; }
	function AddStock(FileStock $Stock)
	{
		if($Stock->Mail())
		b::$Warning[]="این اظهارنامه در لیست ذخیره دیگری وجود داشت! کوتاژ: ".$Stock->File()->Cotag();
		if(!($this->Stock->contains($Stock)))
		{
			$this->Stock->add($Stock);
			$Stock->SetMail($this);
		}
	}
	abstract function Source();
	abstract function Dest();
	abstract function PersianSource();
	abstract function PersianDest();
	abstract function GetProgress();
	function Box()
	{
		if($this->State()==self::STATE_EDITING)
			return $this->Stock();
		elseif($this->State()==self::STATE_INWAY or $this->State()==self::STATE_GETTING)
			return $this->GetProgress();
		else 
			return $this->MyBox();
	}
	/**
	 * 
	 * @param ReviewFile $File
	 * @param string $Error
	 */
	protected function UpdateStock(ReviewFile $File,$Error)
	{
		if(!$File->Stock())
		{
			$Stock=new FileStock($File,$this,$Error);
			ORM::Persist($Stock);
		}
		else
		{
			if($File->Stock()->Mail()->ID()!=$this->ID())
				j::Log("Unexpected", "Stock from another mail exists . Cotag:".$File->Cotag().", StockID:".$File->Stock()->ID().". at model/mail");
			$File->Stock()->SetError($Error);
			$File->Stock()->SetEditTimestamp(time());
			$this->AddStock($File->Stock());
		}
	}
	protected function RemoveOldStocks($time)
	{
		foreach($this->Stock as $s)
		if($s->EditTimestamp()<$time)
		{
			$s->File()->SetStock(null);
			$this->Stock->removeElement($s);
			$s->SetMail(null);
			ORM::Delete($s);
		}
	}
	
	function Save($Files, $RemoveCalled, &$Error)
	{
		if($this->State()==self::STATE_EDITING)
		{
			$this->RetouchTimestamp=time();
		}
		else
		{
			$Error[]="امکان ذخیره کردن وجود ندارد. وضعیت نامه: ".$this->PersianState();
			return 1;	
		}
		$ErrorCount=0;
		$time=time();
		$T=$this->Type();
		foreach ($Files as $File)
		{
			if(!($File instanceof ReviewFile))
			{
				$Error[]=strval($File);
				continue;
			}
			elseif($File->Stock())
			{
				if($File->Stock()->Mail()->ID()!=$this->ID())
				{
					$Error[]="شماره کوتاژ  ".$File->Cotag()." در نامه ی دیگری قرار دارد که هنوز بسته نشده است. شناسه نامه".$File->Stock()->Mail()->ID();
					$ErrorCount++;
					continue;
				}
			}
			if(!$File->Stock() or !$File->Stock()->Act())
			{
				//$P=ORM::Query("ReviewProgress".$T)->AddToFile($File,$this,false);//progress is not persist, it is just for error reporting
				//$r="ReviewProgress".$T;
				//$P = new {$r}();
				switch ($T){
					case "Give":
						$P = new ReviewProgressGive($File,$this);
						break;
					case "Send":
						$P = new ReviewProgressSend($File,$this);
						break;
					case "Receive":
						$P = new ReviewProgressReceive($File,$this);
						break;
					default:
						throw new Exception("model.mail Exception");
				}
				$ch = $P->Check();
				if(is_string($ch))
				{
					//$Error[]=$ch;
					$E = $ch;
					$ErrorCount++;
				}
				else
					$E=null;
				$this->UpdateStock($File, $E);
			}
			
		}
		if($RemoveCalled)
		foreach($this->Stock as $s)
		if($s->EditTimestamp()<$time)
		{
			$s->File()->SetStock(null);
			$this->Stock->removeElement($s);
			$s->SetMail(null);
			ORM::Delete($s);
		}
		return $ErrorCount;
	}
	/**
	 * give  | send | receive
	 * @param array $Files
	 * @param boolean $RemoveCalled
	 * @param array $Error
	 * @return boolean
	 */
	function Act($Files, $RemoveCalled, &$Error)
	{
		$SaveResult=$this->Save($Files, $RemoveCalled, $Error);
		if($Files AND $SaveResult===0)
		{
			$T=$this->Type();
			foreach ($Files as $File)
			{
				if(!($File instanceof ReviewFile) or !$File->Stock())// $File->Stock() maybe is null because the  function 'Save' deletes some stocks
				continue;
				if(!$File->Stock()->Act())
				{
					$P=ORM::Query("ReviewProgress".$T)->AddToFile($File,$this);//persist
					if(is_string($P))
					{
						$faulty=true;
						$this->UpdateStock($File, $P);
						$Error[]=$P;
						$Error[]="تعدادی از اظهارنامه ها ارسال نشد. این یک خطای ناجور است. در صورت مشاهده آن به مسئولین نرم افزار اطلاع دهید.";
						return false;
					}
					else // progress has been persisted successfully
					{
						
						$File->Stock()->SetAct(true);
						$this->UpdateStock($File, "انجام شد");
					}
				}
			}
			if($faulty)
				$this->StateEditingFaulty();
			return true;
		}
		else
			return false;
		
	}
	function Complete($Files, $RemoveCalled, &$Error)
	{
		if($this->Act($Files, $RemoveCalled, $Error))
		{
			return $this->StateClosed();
		}
		else 
		return false;
	}
	function __construct($Num=null, $Subject=null, $Description=null)
	{
		$this->Num=$Num;
		$this->Subject=$Subject;
		$this->Description=$Description;
		$this->State=0;
		$this->StateEditing();
		$this->Stock= new ArrayCollection();
	}
	function Edit($Num=null, $Subject=null, $Description=null)
	{
		if(!$this->CanEdit())
			return false;
		$this->Num=$Num;
		$this->Subject=$Subject;
		$this->Description=$Description;
		return true;
	}
}

use \Doctrine\ORM\EntityRepository;
class MailRepository extends EntityRepository
{
	
}