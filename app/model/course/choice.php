<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity 
 * @entity(repositoryClass="CourseChoiceRepository")
 * @author dariush_jafari
 *
 */
class CourseChoice
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
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Title;
	public function Title()
	{
		return $this->Title;
	}
	
	/**
	* @Column(type="string", nullable=true)
	* @var string
	*/
	protected $Description;
	public function Description()
	{
		return $this->Description;
	}
	
	/**
	 * @OneToMany(targetEntity="CoursePoll", mappedBy="CourseChoice")
	 * @var CoursePoll
	 */
	protected $CoursePoll;
	function CoursePoll(){ return $this->CoursePoll; }
	
	public function __construct($Title, $Desc)
	{
		$this->Title = $Title;
		$this->Description = $Desc;
		$this->CoursePoll = new ArrayCollection();
	}	
}

use \Doctrine\ORM\EntityRepository;
class CourseChoiceRepository extends EntityRepository
{
	public function GetAll()
	{
		$r=j::ODQL("SELECT U FROM CourseChoice U");
		return $r;
	}
	function GetAllWithPollScore(){
		$r = j::DQL('SELECT C.Title as title,sum(P.Score) as score FROM CourseChoice C join C.CoursePoll P GROUP BY C.Title order by score desc');
		return $r;
	}
	function Add($Title, $Desc){
		$r = new CourseChoice($Title, $Desc);
		ORM::Persist($r);
		return $r;
	}
	
}