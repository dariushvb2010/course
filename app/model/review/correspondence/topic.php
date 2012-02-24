<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewCorrespondenceTopicRepository")
 * */
class ReviewCorrespondenceTopic
{
	 /**
     * @GeneratedValue @Id @Column(type="integer")
     * @var integer
     */
    public $ID;
	function ID()
	{
		return $this->ID;
	}
	
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $Topic;
	public function Topic()
	{
		return $this->Topic;
	}
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Comment;
	public function Comment()
	{
		return $this->Comment;
	}
	
	/**
	* @OneToMany(targetEntity="ReviewProgressManualcorrespondence", mappedBy="Subject")
	* @var ArrayCollection
	*/
 	protected $Correspondence;
	function Correspondence()
	{
		return $this->Correspondence;
	}
	function __construct($topic=null,$comment=null)
	{
		if($topic)
			$this->Topic=$topic;
		if($comment)
			$this->Comment=$comment;
		else 
			$this->Comment="";
	}
	public static function Topics()
	{
		return ORM::Query(new ReviewCorrespondenceTopic())->GetTopics();
	}
	
	
}


use \Doctrine\ORM\EntityRepository;
class ReviewCorrespondenceTopicRepository extends EntityRepository
{
	public function GetTopics()
	{
		$r=j::DQL("SELECT T FROM ReviewCorrespondenceTopic AS T");
		return $r;	
	}
	/**
	*
	* Enter description here ...
	* @param string
	* @return string on error object on sucsess
	*/
	public function Add($Subject,$Comment)
	{
		if ($Subject==null)
		{
			$Error="لطفا یک عنوان انتخاب نمایید.";
			return $Error;
		}
		else
		{
			if($Comment==null)
				$Comment="";
			$T=new ReviewCorrespondenceTopic($Subject,$Comment);
			ORM::Write($T);
			return true;
		}
	
	}
	public function Delete(ReviewCorrespondenceTopic $T)
	{
		j::DQL("DELETE FROM ReviewCorrespondence AS C WHERE C.ID=? ",$T->ID());
	
	}
}