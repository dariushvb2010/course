<?php
/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressEbtalRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressEbtal extends ReviewProgress
{
	function __construct(ReviewFile $File=null)
	{
		parent::__construct($File, null);
	}
	function  Summary()
	{
		return "اظهارنامه توسط مدیر ابطال گردید و به دفتر کوتاژ تحویل شد.";	
	}
	
	function Title()
	{
		return "ابطال";
	}
	function Manner()
	{
		return "Ebtal";
	}
}
use \Doctrine\ORM\EntityRepository;
class ReviewProgressEbtalRepository extends EntityRepository
{
	public function EbtalCotag($Cotag)
	{
		if(b::CotagValidation($Cotag)==false)
		{
			$Error=v::Ecnv();
			return $Error;
		}
		$Cotag=$Cotag*1;
		
		$File=b::GetFile($Cotag);
		if ($File==null)
		{
			$Error=v::Ecnf($Cotag);
			return $Error;
		}
		
		$Ebtal= new ReviewProgressEbtal($File);
		$ch=$Ebtal->Check();
		if(is_string($ch))
			return $ch;
		
		
		$S=$File->Stock();
		if($S)
		{
			ORM::Delete($S);
		}
		$A=$File->Alarm();
		if(count($A))
		{
			foreach ($A as $a)
				ORM::Delete($a);
		}
		$proglist=$File->Progress();
		if(count($proglist))
		{
			foreach ($proglist as $p)
				$p->kill();
		}

		//$Ebtal= new ReviewProgressEbtal($File, true);
		$Ebtal->Apply();
		
		ORM::Persist($Ebtal);
		
		return true;			
	}
}
