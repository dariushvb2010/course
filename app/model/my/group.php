<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * this class is used to save constant and legal information about alarms
 * @Entity Table(name="MyGroup")
 * @Entity(repositoryClass="MyGroupRepository")
 * */
class MyGroup
{
	
	/**
	* @GeneratedValue
	*  @Id @Column(type="integer")
	* @var integer
	*/
	protected $ID;
	function ID()
	{
		return $this->ID;
	}
	const Title_Admin = 'Admin';
	/**
	 * @Column(type="string", unique=true)
	 * @var string
	 */
	protected $Title;
	function Title()
	{
		return $this->Title;
	}
	
	/**
	* @Column(type="string", nullable=true)
	* @var string
	*/
	protected $PersianTitle;
	function PersianTitle()
	{
		return $this->PersianTitle;
	}
	/**
	 * Reliable Persian Title, if PersianTitle==null return Title
	 */
	function RTitle()
	{
		return $this->PersianTitle ? $this->PersianTitle : $this->Title;
	}
	/**
	* @Column(type="string", nullable=true)
	* @var string
	*/
	protected $Description;
	function Description()
	{
		return $this->Description;
	}
	/**
	 * @OneToMany(targetEntity="MyUser", mappedBy="Group")
     * @var arrayCollectionOfMyUser
     */
    protected $User;
    function User()
    {
        return $this->User;
    }
	
	function __construct($Title=null, $PersianTitle=null, $Description=null)
	{
			$this->Title=$Title;
			$this->PersianTitle=$PersianTitle;
			$this->Description=$Description;
			$this->User=new ArrayCollection();
	}
	static function GetGroup($GroupName)
	{
		return ORM::Find1("MyGroup", "Title", $GroupName);
	}
}


use \Doctrine\ORM\EntityRepository;
class MyGroupRepository extends EntityRepository
{
	
	
	public static function Add($Title, $PersianTitle=null, $Description=null)
	{
		if ($Title==null)
		{
			$Error="رکورد نمی تواند بدون نام باشد.";
			return $Error;
		}
		else
		{
			$F=ORM::Find1("MyGroup", "Title",$Title);
			if($F)
			{
				$Error="این نام قبلا ثبت شده است.";
				return $Error;
			}
			$G=new MyGroup($Title, $PersianTitle, $Description);
			ORM::Write($G);
			return $G;
		}
	
	}
	public function GetAll()
	{
		$r=j::ODQL("SELECT G FROM MyGroup G");
		return $r;	
	}
	
	public function Delete(ConfigAlarm $A)
	{
		if($C->DeleteAccess())
		{
			j::DQL("DELETE FROM ConfigAlarm AS C WHERE C.ID=? ",$A->ID());
			return true;
		}
		else 
		{
			return "امکان حذف کردن وجود ندارد.";
		}
	}
}