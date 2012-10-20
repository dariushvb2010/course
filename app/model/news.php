<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity 
 * @entity(repositoryClass="NewsRepository")
 * @author dariush_jafari
 *
 */
class News
{
	/**
     * @GeneratedValue @Id @Column(type="integer")
     * @var string
     */
    public $ID;
	public function ID(){	return $this->ID; }
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Title;
	public function Title(){	return $this->Title; }
	function SetTitle($val){ $this->Title = $val; }
	/**
	* @Column(type="string", nullable=true)
	* @var string
	*/
	protected $Description;
	public function Description(){ return $this->Description; }
	function SetDescription($val){ $this->Description = $val; }
	
	public function __construct($Title, $Desc)
	{
		$this->Title = $Title;
		$this->Description = $Desc;
	}	
}

use \Doctrine\ORM\EntityRepository;
class NewsRepository extends EntityRepository
{
	public function GetAllArray()
	{
		$r=j::DQL('SELECT N FROM News N');
		return $r;
	}
	function Add($Title, $Desc){
		$r = new News($Title, $Desc);
		ORM::Persist($r);
		return $r;
	}
	
}