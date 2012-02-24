<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * 
 * @Entity Table(name="ReviewDossier")
 * @Entity(repositoryClass="ReviewDossierRepository")
 **/
class ReviewDossier
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
	*
	* @OneToOne(targetEntity="ReviewFile", mappedBy="Dossier")
	* @var ReviewFile
	*/
	protected $File;
	function File()
	{
		return $this->File;
	}
    
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	private $Andicator;
	function Andicator(){ return $this->Andicator;}
}


use \Doctrine\ORM\EntityRepository;
class ReviewDossierRepository extends EntityRepository
{
	
	
}