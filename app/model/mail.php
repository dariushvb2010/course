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
	/**
	* 
	* @Column(type="integer")
	* @var integer
	*/
	protected $State;
	function State(){return $this->State;}
	function SetState($state){ $this->State=$state; }
	/**
	* @Column(type="integer", nullable=true)
	* @var integer
	*/
	protected $EventTimestamp;
	function EventTimestamp(){ return $this->EventTimestamp;}
	/**
	 * the last save timestamp
	* @Column(type="integer", nullable=true)
	* @var integer
	*/
	protected $SaveTimestamp;
	function SaveTimestamp(){return $this->SaveTimestamp;}
	
	/**
	* @Column(type="string",nullable=true)
	* @var string
	*/
	protected $Comment;
	public function Comment() { return $this->Comment; }
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
	protected function UpdateStock(ReviewFile $File,$Error)
	{
		if(!$File->Stock())
		{
			$Stock=new FileStock($File,$this,$Error);
			ORM::Persist($Stock);
		}
		else
		{
			$File->Stock()->SetError($Error);
			$this->AddStock($File->Stock());
		}
	}
	function __construct($Num=null,$Comment=null)
	{
		$this->Num=$Num;
		$this->Comment=$Comment;
		$this->State=1;
		$this->Stock= new ArrayCollection();
	}
}