<?php
/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressEbtalRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressEbtal extends ReviewProgress
{
	function __construct(ReviewFile $File=null, $IfPersist=true)
	{
		parent::__construct($File, null, $IfPersist);
	}
	function  Summary()
	{
		return "اظهارنامه توسط مدیر ابطال گردید و به دفتر کوتاژ تحویل شد.";	
	}
	
	function Title()
	{
		return "ابطال";
	}
	function Event()
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
			$Error=" کوتاژ ناصحیح است.کوتاژ باید هفت رقمی باشد.";
			return $Error;
		}
		$Cotag=$Cotag*1;
		
		$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
		if ($File==null)
		{
			$Error="این کوتاژ وصول نشده است.";
			return $Error;
		}
		
		$Ebtal= new ReviewProgressEbtal($File, false);
		$ch=$Ebtal->Check();
		if(is_string($ch))
			return $ch;
		
		$Ebtal= new ReviewProgressEbtal($File, true);
		$Ebtal->Apply();
		
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
		
		ORM::Persist($Ebtal);
		
		return true;			
	}
}
