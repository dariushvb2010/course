<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity Table(name="MySettingManager")
 * @entity(repositoryClass="MySettingManagerRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discriminator", type="string")
 * Application Specific User Settings for the manager
 * @author dariush_jafari
 *
 */
class MySettingManager extends MySetting
{
	
	/**
	* @Column(type="boolean")
	* @var boolean
	* show the retired reviewers in the reviewers list or not
	*/
	protected $ShowRetireds;
	public function ShowRetireds()
	{
		return $this->ShowRetireds;
	}
	public function SetShowRetireds($b)
	{
		$this->ShowRetireds=$b;
	}
	public function __construct(MyUser $User=null,$ShowFirstname=0, $ShowRetireds=0)
	{
		parent::__construct($User,$ShowFirstname);
		$this->ShowRetireds=$ShowRetireds;
	}
	function Make($ShowRetireds)
	{
		$b=($ShowRetireds=="1" ? 1 : 0);
		if(isset($ShowRetireds))
		{
			$this->ShowRetireds=$b;
			return "تغییرات ذخیره شد.";
		}
		return false;
		//ORM::Flush();
	}
	/**
	 * 
	 * Make an autoform from existing settings in this class
	 * @return AutoformPlugin
	 */
	function MakeForm()
	{
		$SHR=$this->ShowRetireds;
		$f=new AutoformPlugin();
		$f->AddElement(array(
					"Type"=>"select",
					"Name"=>"ShowRetireds",
					"Label"=>"نمایش کارشناسان غیرفعال دائمی",
					"Options"=>array("0"=>"عدم نمایش",
									"1"=>"نمایش"),
					"Default"=>"$SHR"
		));
		$f->AddElement(array(
					"Type"=>"submit",
					"Value"=>"اعمال تغییرات",
		));
		return $f;
	}
}

use \Doctrine\ORM\EntityRepository;
class MySettingManagerRepository extends EntityRepository
{
	
	
}