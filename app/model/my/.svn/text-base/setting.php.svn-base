<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity Table(name="MySetting")
 * @entity(repositoryClass="MySettingRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * @DiscriminatorMap({"Base" = "MySetting",
 * "Manager"="MySettingManager"
 * })
 * Application Specific User Settings
 * @author dariush_jafari
 *
 */
class MySetting 
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
	* @Column(type="boolean")
	* @var boolean
	*/
	protected $ShowFirstname;
	public function ShowFirstname()
	{
		return $this->ShowFirstname;
	}
	/**
	* @ManyToOne(targetEntity="MyUser", inversedBy="Setting")
	* @JoinColumn(name="UserID", referencedColumnName="ID")
	*/
	protected $User;
	function User()
	{
		return $this->User;
	}
	/**
	* Assigns the user here and assigns this setting to the file
	* @param ReviewFile $File
	*/
	function AssignUser(MyUser $User)
	{
		$this->User=$User;
		$User->Setting()->add($this);
	}
	public function __construct(MyUser $User=null,$ShowFirstname=0)
	{
		$this->ShowFirstname=$ShowFirstname;
		if ($User) $this->AssignUser($User);
	}
	//abstract function MakeForm();
}

use \Doctrine\ORM\EntityRepository;
class MySettingRepository extends EntityRepository
{
	
	
}