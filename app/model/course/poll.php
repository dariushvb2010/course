<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity 
 * @entity(repositoryClass="CoursePollRepository")
 * @author dariush_jafari
 *
 */
class CoursePoll
{
	/**
     * @GeneratedValue @Id @Column(type="integer")
     * @var string
     */
    public $ID;
	public function ID()
	{
		return $this->ID;
	}
	const Score_high = 3;
	const Score_medium = 2;
	const Score_low = 1;
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Score;
	public function Score(){ return $this->Score; }
	/**
	 * @ManyToOne(targetEntity="MyUser", inversedBy="CoursePoll")
	 * @JoinColumn(name="UserID", referencedColumnName="ID")
	 * @var MyUser
	 */
	protected $User;
	function User(){ return $this->User; }
	/**
	 * @ManyToOne(targetEntity="CourseChoice", inversedBy="CoursePoll")
	 * @JoinColumn(name="ChoiceID", referencedColumnName="ID")
	 * @var CourseChoice
	 */
	protected $CourseChoice;
	function CourseChoice(){ return $this->CourseChoice; }
	function SetCourseChoice(CourseChoice $val){ $this->CourseChoice = $val; }
	
	public function __construct(MyUser $User, CourseChoice $CourseChoice, $Score)
	{
		$this->Score = $Score; 
		$this->User = $User;
		$this->CourseChoice = $CourseChoice;
	}	
}

use \Doctrine\ORM\EntityRepository;
class CoursePollRepository extends EntityRepository
{
	public function GetAll()
	{
		$r=j::ODQL("SELECT U FROM CoursePoll U");
		return $r;
	}
	public function GetByUserAndScore(MyUser $User, $Score)
	{
		$r=j::ODQL("SELECT P FROM CoursePoll P join P.User U where U=? and P.Score=?",$User, $Score);
		return $r;
	}
	function Add(MyUser $User, CourseChoice $CourseChoice, $Score){
		$p = new CoursePoll($User, $CourseChoice, $Score);
		ORM::Persist($p);
		return $p;
	}
	
}