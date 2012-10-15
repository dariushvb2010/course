<?php
use Doctrine\Common\Cache\ArrayCache;

use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity 
 * @entity(repositoryClass="MyUserRepository")
 * Application Specific User
 * @author dariush_jafari
 *
 */
class MyUser extends Xuser
{
	function ID(){
		return $this->ID;
	}
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Firstname;
	public function Firstname()
	{
		$r=$this->Firstname;
		return (mb_strlen($r,'utf-8')>15?mb_substr($r, 0,12,'utf-8').'...':mb_substr($r, 0,15,'utf-8'));
	}
	public function SetFirstname($value){
		$this->Firstname=$value;
	}
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Lastname;
	public function Lastname()
	{
		
		$r=$this->Lastname;
		return (mb_strlen($r,'utf-8')>15?mb_substr($r, 0,12,'utf-8').'...':mb_substr($r, 0,15,'utf-8'));
	}
	public function SetLastname($value){
		$this->Lastname=$value;
	}

	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Codemelli;
	public function Codemelli()
	{
		return $this->Codemelli;
	}
	public function SetCodemelli($value){
		$this->Codemelli=$value;
	}
	/**
	* @Column(type="boolean")
	* @var boolean
	* 0= male
	* 1=female
	*/
	protected $gender;
	public function Gender()
	{
		if($this->gender){
			return 'خانم';
		}else{
			return 'آقای';
		}
	}
	public function SetGender($iswoman)
	{
		$this->gender=$iswoman;
	}
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $SaleVorod;
	function SaleVorod(){ return $this->SaleVorod; }
	
	/**
	 * @OneToMany(targetEntity="CoursePoll", mappedBy="User")
	 * @var CoursePoll
	 */
	protected $CoursePoll;
	function CoursePoll(){ return $this->CoursePoll;	}
	/**
	 * @ManyToOne(targetEntity="MyGroup", inversedBy="User")
	 * @JoinColumn(name="Group1ID",referencedColumnName="ID", nullable=false)
	 * @var MyGroup
	 */
	protected $Group;
	function Group()
	{
		return $this->Group;
	}
	function SetGroup($Group)
	{
		$this->Group=$Group;
		$Group->User()->add($this);
	}
	function GroupTitle(){
		return $this->Group->Title();
	}
	function GroupRTitle()
	{
		if($this->Group)
			return $this->Group->RTitle();
	}
	/**
	 * if this user is in the group $groupTitle
	 * @param string $groupTitle
	 * @return boolean
	 */
	function Is($groupTitle){
		$g = $this->Group();
		if($g)
			if($g->Title() == $groupTitle)
				return true;
	}
	
	/**
	*
	* @OneToMany(targetEntity="MySetting", mappedBy="User")
	* @var MySetting
	* this is an array of settings of the user, every user has some settings 
	*/
	protected $Setting;
	function Setting()
	{
		return $this->Setting;
	}
	function MainSetting()
	{
		if($this->Setting)
			return $this->Setting[0];
		else 
			return null;
	}
	
	public function __construct($Username=null,$Password=null,$Gender=0,$Firstname="",$Lastname="",$Codemelli="",$isReviewer=false,$Email="",$Group=null, $SaleVorod)
	{
		if ($Username)
		{
			parent::__construct($Username,$Password,$Email);
			$this->Firstname=$Firstname;
			$this->gender=$Gender;
			$this->Lastname=$Lastname;
			$this->SaleVorod = $SaleVorod;
			$this->SetGroup($Group);
			$this->SetCodemelli($Codemelli);
			$this->Setting= new ArrayCollection();
			$this->CoursePoll = new ArrayCollection();
		}
	}	
	
	function getFullName(){
		return $this->Gender()." ".$this->Firstname." ".$this->Lastname();
	}
	/**
	 * @return MyUser
	 */
	public static function CurrentUser()
	{
		return self::getUser(j::UserID());
	}
	
	public static function getUser($UserID)
	{
		$s = ORM::Find("MyUser",$UserID);
		return $s;
	}
}

use \Doctrine\ORM\EntityRepository;
class MyUserRepository extends EntityRepository
{
	public function GetAll()
	{
		$r=j::ODQL("SELECT U FROM MyUser U");
		return $r;
	}
	/**
	 * @author Morteza Kavakebi
	 * @param integer $ID
	 * @return MyUser
	 */
	public function getUserByID($ID)
	{
		return j::ODQL("SELECT U FROM MyUser U WHERE U.ID=?",$ID);
	}
	
}