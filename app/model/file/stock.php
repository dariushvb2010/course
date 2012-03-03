<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * has to save the file info
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
	* @Column(type="string", nullable=true)
	* @var integer
	*/
	protected $Error;
	function Error(){ return $this->Error; }
	function SetError($Error){ $this->Error=$Error; }
	/**
	 * @OneToOne(targetEntity="ReviewFile", inversedBy="Stock")
     * @JoinColumn(name="FileID", referencedColumnName="ID", nullable=false)
	 * @var ReviewFile
	 */
	protected $File;
	function File(){ return $this->File; }
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
	function SetMail(Mail $Mail){ $this->Mail=$Mail; }
	function AssignMail(Mail $Mail)
	{
		$Mail->AddStock($this);
	}
	
	
	function __construct(ReviewFile $File=null, Mail $Mail=null, $Error=null)
	{
		$this->AssignFile($File);
		$this->AssignMail($Mail);
		$this->Error=$Error;
	}
}
use \Doctrine\ORM\EntityRepository;
class FileStockRepository extends EntityRepository
{
	public function Add()
	{
		
	}
}