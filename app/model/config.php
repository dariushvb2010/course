<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity Table(name="Config")
 * @Entity(repositoryClass="ConfigRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * @DiscriminatorMap({"Base"="Config",
 * 	"Main"="ConfigMain"
 * })
 * */
abstract class Config
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
	 * Can the User delete this record?
	 * @Column(type="boolean")
	 * @var boolean
	 */
	protected $DeleteAccess;
	public function DeleteAccess()
	{
		return $this->DeleteAccess;
	}
	/**
	* @Column(type="string", nullable=true)
	* @var string
	*/
	protected $Comment;
	public function Comment()
	{
		return $this->Comment;
	}
	function SetComment($Comment)
	{
		$this->Comment=$Comment;
	}
	/**
	* @Column (type="string", nullable=true,unique=true)
	* @var string
	*/
	protected $Style;
	function Style()
	{
		return $this->Style;
	}
	function __construct($DeleteAccess=true, $Comment=null, $Style=null)
	{
		$this->DeleteAccess=$DeleteAccess;
		$this->Comment=$Comment;
		$this->Style=null;
	}
}


use \Doctrine\ORM\EntityRepository;
class ConfigRepository extends EntityRepository
{
	
}