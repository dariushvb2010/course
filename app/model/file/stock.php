<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * has to save the file info
 * only usefull for mail
 * saves the files in the mail and 
 * has a 1-1 relationship with the ReviewFile
 * @author dariush jafari
 * @Entity
 * @Entity(repositoryClass="FileStockRepository")
 */
class FileStock
{
	
	/**
	* @GeneratedValue @Id @Column(type="integer")
	* @var integer
	*/
	protected $ID;
	function ID(){ return $this->ID; }
	/**
	* @Column(type="integer")
	* @var integer
	*/
	protected $EditTimestamp;
	function EditTimestamp(){ return $this->EditTimestamp; }
	protected function SetEditTimestamp($Time){ $this->EditTimestamp=$Time; }
	/**
	 * @Column(type="boolean")
	 * @var boolean
	 */
	protected $Act;
	function Act(){ return $this->Act; }
	function SetAct($Act){ $this->Act=$Act; }
	/**
	* @Column(type="string", nullable=true)
	* @var string
	*/
	protected $Error;
	function Error(){ return $this->Error; }
	function SetError($Error){ $this->Error=$Error; }
	/**
	 * @OneToOne(targetEntity="ReviewFile", inversedBy="Stock")
     * @JoinColumn(name="FileID", referencedColumnName="ID")
	 * @var ReviewFile
	 */
	protected $File;
	function File(){ return $this->File; }
	function SetFile($File){$this->File=$File; }
	function AssignFile(ReviewFile $File)
	{
		$this->File=$File;
		$File->SetStock($this);
	}
	/**
	* @ManyToOne(targetEntity="Mail", inversedBy="Stock")
	* @JoinColumn(name="MailID",referencedColumnName="ID", nullable=false)
	* @var Mail
	*/
	protected $Mail;
	function Mail(){ return $this->Mail; }
	function SetMail($Mail){ $this->Mail=$Mail; }
	function AssignMail(Mail $Mail)
	{
		$Mail->AddStock($this);
	}
	function Update($Error)
	{
		$this->SetError($Error);
		$this->SetEditTimestamp(time());
	}
	function Cotag()
	{
		if($this->File)
			return $this->File->Cotag();
	}
	function __construct(ReviewFile $File=null, Mail $Mail=null, $Error=null)
	{
		$this->AssignFile($File);
		$this->AssignMail($Mail);
		$this->Error=$Error;
		$this->Act=false;
		$this->SetEditTimestamp(time());
	}
}
use \Doctrine\ORM\EntityRepository;
class FileStockRepository extends EntityRepository
{
	public function Add()
	{
		
	}
}